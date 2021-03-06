<?php

namespace Duo\AdminBundle\Form\Type;

use Duo\AdminBundle\Form\DataTransformer\UrlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UrlType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->addViewTransformer(new UrlTransformer());
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TextType::class;
	}
}
