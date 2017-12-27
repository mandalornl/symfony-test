<?php

namespace Duo\AdminBundle\Event\Behavior;

use Duo\AdminBundle\Entity\Behavior\SortInterface;
use Symfony\Component\EventDispatcher\Event;

final class SortEvent extends Event
{
	/**
	 * @var SortInterface
	 */
	private $entity;

	/**
	 * SortEvent constructor
	 *
	 * @param SortInterface $entity
	 */
	public function __construct(SortInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Set entity
	 *
	 * @param SortInterface $entity
	 *
	 * @return SortEvent
	 */
	public function setEntity(SortInterface $entity): SortEvent
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * Get entity
	 *
	 * @return SortInterface
	 */
	public function getEntity(): ?SortInterface
	{
		return $this->entity;
	}
}