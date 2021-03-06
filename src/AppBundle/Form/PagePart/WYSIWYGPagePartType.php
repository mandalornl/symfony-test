<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\WYSIWYGPagePart;
use Duo\AdminBundle\Form\Type\WYSIWYGType;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;

class WYSIWYGPagePartType extends AbstractPartType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('value', WYSIWYGType::class, [
			'label' => false
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return WYSIWYGPagePart::class;
	}
}
