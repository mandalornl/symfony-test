<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior\SoftDeletableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SoftDeletableTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doDeleteAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SoftDeletableInterface)
		{
			return $this->softDeletableInterfaceNotImplemented($id);
		}

		$entity->delete();

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doUndeleteAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SoftDeletableInterface)
		{
			return $this->softDeletableInterfaceNotImplemented($id);
		}

		$entity->undelete();

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return $this->redirect("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Soft deletable interface not implemented
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	private function softDeletableInterfaceNotImplemented(int $id): Response
	{
		$interface = SoftDeletableInterface::class;
		return new Response("Entity of type '{$this->getEntityClassName()}' with id '{$id}' doesn't implement '{$interface}'", 500);
	}

	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function deleteAction(Request $request, int $id);

	/**
     * Undelete entity
     *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function undeleteAction(Request $request, int $id);
}