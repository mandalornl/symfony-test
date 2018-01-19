<?php

namespace Duo\BehaviorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\SecurityBundle\Helper\TokenHelper;

class PublishSubscriber implements EventSubscriber
{
	/**
	 * @var TokenHelper
	 */
	private $tokenHelper;

	/**
	 * @var bool
	 */
	private $isPublished;

	/**
	 * PublishSubscriber constructor
	 *
	 * @param TokenHelper $tokenHelper
	 */
	public function __construct(TokenHelper $tokenHelper)
	{
		$this->tokenHelper = $tokenHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::postLoad,
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On post load event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof PublishInterface)
		{
			return;
		}

		$this->isPublished = $entity->isPublished();
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setBlame($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setBlame($args->getObject());
	}

	/**
	 * Set blame
	 *
	 * @param object $entity
	 */
	private function setBlame($entity)
	{
		if (!$entity instanceof PublishInterface)
		{
			return;
		}

		if (($user = $this->tokenHelper->getUser()) === null)
		{
			return;
		}

		if ($this->isPublished && !$entity->isPublished())
		{
			$entity->setUnpublishedBy($user);
			return;
		}

		$entity->setPublishedBy($user);
	}
}