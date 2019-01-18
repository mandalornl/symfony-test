<?php

namespace Duo\FormBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Duo\AdminBundle\Helper\MailerHelper;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPart\FormPartInterface;
use Duo\FormBundle\Entity\FormSubmission;
use Duo\FormBundle\Entity\FormTranslation;
use Duo\FormBundle\Form\FormViewType;
use Duo\PartBundle\Entity\Property\PartInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/form", name="duo_form_")
 */
class FormController extends Controller
{
	/**
	 * View action
	 *
	 * @Route("/{uuid}", name="view", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 * @param FormFactoryInterface $formFactory
	 * @param MailerHelper $mailerHelper
	 * @param string $uuid
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function viewAction(
		Request $request,
		FormFactoryInterface $formFactory,
		MailerHelper $mailerHelper,
		string $uuid
	): Response
	{
		$className = Form::class;

		/**
		 * @var Form $entity
		 */
		$entity = $this->getDoctrine()->getRepository($className)->findOneBy([
			'uuid' => $uuid
		]);

		if ($entity === null)
		{
			return $this->json([
				'error' => "Entity '{$className}::{$uuid}' not found",
				'message' => $this->get('translator')->trans('duo.admin.error', [], 'flashes')
			]);
		}

		$formParts = $this->getFormParts($request, $entity);

		if ($formParts === null)
		{
			$interface = PartInterface::class;
			$error = "Entity '{$className}::{$uuid}' doesn't implement '{$interface}'";

			return $this->json([
				'error' => $error,
				'message' => $this->get('translator')->trans('duo.admin.error', [], 'flashes')
			]);
		}

		$form = $formFactory->createNamed('form_' . $entity->getUuid(), FormViewType::class, null, [
			'action' => $this->generateUrl('duo_form_view', [
				'uuid' => $entity->getUuid(),
				'locale' => $request->getLocale()
			]),
			'formParts' => $formParts
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$data = $form->getData();

			$submissionData = [];

			foreach ($formParts as $index => $formPart)
			{
				if (!isset($data[$index]))
				{
					continue;
				}

				/**
				 * @var FormPartInterface $formPart
				 */
				$submissionData[$formPart->getLabel()] = trim($data[$index]);
			}

			$submission = (new FormSubmission())
				->setName($entity->getName())
				->setLocale($request->getLocale())
				->setData($submissionData)
				->setForm($entity);

			// keep submission
			if ($entity->getKeepSubmissions())
			{
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($submission);
				$manager->flush();
			}

			/**
			 * @var FormTranslation $translation
			 */
			$translation = $entity->translate($request->getLocale());

			// send submission to
			if ($entity->getSendSubmissionsTo() &&
				!count($this->get('validator')->validateProperty($entity, 'sendSubmissionTo')))
			{
				$message = $mailerHelper
					->prepare('@DuoForm/Mail/form_submission.mjml.twig', [
						'entity' => $entity,
						'submission' => $submission
					])
					->setTo($entity->getSendSubmissionsTo());

				$this->get('mailer')->send($message);
			}

			return $this->json([
				'message' => $translation->getMessage(),
				'redirect' => $entity->getPage() !== null ? $this->generateUrl('_url', [
					'url' => $entity->getPage()->translate($request->getLocale())->getUrl(),
					'_locale' => $request->getLocale()
				]) : null
			]);
		}

		return $this->json([
			'html' => $this->renderView('@DuoForm/Form/view.html.twig', [
				'form' => $form->createView()
			])
		]);
	}

	/**
	 * Get form parts
	 *
	 * @param Request $request
	 * @param Form $entity
	 *
	 * @return Collection
	 */
	private function getFormParts(Request $request, Form $entity): ?Collection
	{
		if (!$entity instanceof PartInterface)
		{
			if ($entity instanceof TranslateInterface)
			{
				$translation = $entity->translate($request->getLocale());

				if ($translation instanceof PartInterface)
				{
					return $translation->getParts();
				}
			}

			return null;
		}

		/**
		 * @var PartInterface $entity
		 */
		return $entity->getParts();
	}
}
