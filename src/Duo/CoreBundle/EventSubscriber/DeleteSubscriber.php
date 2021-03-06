<?php

namespace Duo\CoreBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Symfony\Component\Security\Core\Security;

class DeleteSubscriber implements EventSubscriber
{
	/**
	 * @var Security
	 */
	private $security;

	/**
	 * DeleteSubscriber constructor
	 *
	 * @param Security $security
	 */
	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$this->setDeletedBy($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$this->setDeletedBy($args->getObject());
	}

	/**
	 * Set deleted by
	 *
	 * @param object $entity
	 */
	public function setDeletedBy(object $entity): void
	{
		if (!$entity instanceof DeleteInterface ||
			!$entity->isDeleted() ||
			($user = $this->security->getUser()) === null)
		{
			return;
		}

		$entity->setDeletedBy($user);
	}
}
