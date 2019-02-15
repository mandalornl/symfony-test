<?php

namespace Duo\CoreBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

trait TreeTrait
{
	/**
	 * {@inheritdoc}
	 */
	public function getOffspringIds(int $id, bool $traverse = true): array
	{
		/**
		 * @var EntityRepository $this
		 */
		$className = $this->getClassMetadata()->getName();

		$dql = <<<DQL
SELECT e.id FROM {$className} e WHERE e.parent IN (:ids)
DQL;

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $this->getEntityManager();

		$query = $manager->createQuery($dql);

		$offspring = [];
		$iterations = 100;
		$ids = (array)$id;

		do
		{
			$query->setParameter('ids', $ids);
			$ids = array_column($query->getScalarResult(), 'id');

			$offspring = array_merge($offspring, $ids);
			if (!$traverse)
			{
				break;
			}
		} while (count($ids) && $iterations--);

		return array_map('intval', array_unique($offspring));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParentIds(int $id, bool $traverse = true): array
	{
		/**
		 * @var EntityRepository $this
		 */
		$className = $this->getClassMetadata()->getName();

		$dql = <<<DQL
SELECT IDENTITY(e.parent) id FROM {$className} e WHERE e.id IN(:ids) AND e.parent IS NOT NULL
DQL;

		/**
		 * @var EntityManagerInterface $manager
		 */
		$manager = $this->getEntityManager();

		$query = $manager->createQuery($dql);

		$parents = [];
		$iterations = 100;
		$ids = (array)$id;

		do
		{
			$query->setParameter('ids', $ids);
			$ids = array_column($query->getScalarResult(), 'id');

			$parents = array_merge($parents, $ids);

			if (!$traverse)
			{
				break;
			}
		} while (count($ids) && $iterations--);

		return array_map('intval', array_unique($parents));
	}
}
