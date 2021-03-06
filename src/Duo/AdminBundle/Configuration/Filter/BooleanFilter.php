<?php

namespace Duo\AdminBundle\Configuration\Filter;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Form\Filter\BooleanFilterType;

class BooleanFilter extends AbstractFilter
{
	/**
	 * {@inheritDoc}
	 */
	public function buildFilter(QueryBuilder $builder, array $data): void
	{
		if (!isset($data['value']))
		{
			return;
		}

		$pid = $this->getParamId($data);

		$builder
			->andWhere("{$this->alias}.{$this->property} = :{$pid}")
			->setParameter($pid, (int)$data['value']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return BooleanFilterType::class;
	}
}
