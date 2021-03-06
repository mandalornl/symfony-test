<?php

namespace Duo\CoreBundle\Entity\Property;

interface SortInterface
{
	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return SortInterface
	 */
	public function setWeight(?int $weight): SortInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;
}