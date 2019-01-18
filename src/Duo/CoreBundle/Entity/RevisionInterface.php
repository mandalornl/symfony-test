<?php

namespace Duo\CoreBundle\Entity;

use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;

interface RevisionInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set data
	 *
	 * @param array $data
	 *
	 * @return RevisionInterface
	 */
	public function setData(?array $data): RevisionInterface;

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData(): ?array;

	/**
	 * Set entity
	 *
	 * @param PropertyRevisionInterface $entity
	 *
	 * @return RevisionInterface
	 */
	public function setEntity(?PropertyRevisionInterface $entity): RevisionInterface;

	/**
	 * Get entity
	 *
	 * @return PropertyRevisionInterface
	 */
	public function getEntity(): ?PropertyRevisionInterface;
}
