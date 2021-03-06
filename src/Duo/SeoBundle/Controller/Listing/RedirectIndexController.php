<?php

namespace Duo\SeoBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\Action;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\BooleanFilter;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Duo\SeoBundle\Entity\Redirect;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectIndexController extends AbstractIndexController
{
	use RedirectConfigurationTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('active', 'duo_seo.listing.field.active'))
			->addField(new Field('permanent', 'duo_seo.listing.field.permanent'))
			->addField(new Field('origin', 'duo_seo.listing.field.origin'))
			->addField(new Field('target', 'duo_seo.listing.field.target'))
			->addField(new Field('createdAt', 'duo_seo.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_seo.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new BooleanFilter('active', 'duo_seo.listing.filter.active'))
			->addFilter(new BooleanFilter('permanent', 'duo_seo.listing.filter.permanent'))
			->addFilter(new StringFilter('origin', 'duo_seo.listing.filter.origin'))
			->addFilter(new StringFilter('target', 'duo_seo.listing.filter.target'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_seo.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_seo.listing.filter.modified'));
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Throwable
	 */
	protected function defineActions(Request $request): void
	{
		$this
			->addAction(
				(new Action('duo_seo.listing.action.activate'))
					->setRoute("{$this->getRoutePrefix()}_activate")
			)
			->addAction(
				(new Action('duo_seo.listing.action.deactivate'))
					->setRoute("{$this->getRoutePrefix()}_deactivate")
			);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * Activate action
	 *
	 * @Route(
	 *     path="/activate/{id}.{_format}",
	 *     name="activate",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function activateAction(Request $request, int $id = null): Response
	{
		return $this->handleActivationRequest(function(Redirect $entity)
		{
			$entity->setActive(true);
		}, 'duo_seo.activate_success', $request, $id);
	}

	/**
	 * Deactivate action
	 *
	 * @Route(
	 *     path="/deactivate/{id}.{_format}",
	 *     name="deactivate",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function deactivateAction(Request $request, int $id = null): Response
	{
		return $this->handleActivationRequest(function(Redirect $entity)
		{
			$entity->setActive(false);
		}, 'duo_seo.deactivate_success', $request, $id);
	}

	/**
	 * Handle activation request
	 *
	 * @param \Closure $callback
	 * @param string $message
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleActivationRequest(
		\Closure $callback,
		string $message,
		Request $request,
		int $id = null
	): Response
	{
		$selection = (array)$id ?: $this->getSelection($request);

		if (!count($selection))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo_admin.no_items', [], 'flashes')
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo_admin.no_items', [], 'flashes'));
		}
		else
		{
			$manager = $this->getDoctrine()->getManager();

			foreach (array_chunk($selection, 100) as $ids)
			{
				$entities = $this->getDoctrine()->getRepository($this->getEntityClass())->findBy([
					'id' => $ids
				]);

				foreach ($entities as $entity)
				{
					call_user_func_array($callback, [ $entity ]);

					$manager->persist($entity);
				}

				$manager->flush();
				$manager->clear();
			}

			$manager->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans($message, [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans($message, [], 'flashes'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.origin', 'ASC');
	}
}
