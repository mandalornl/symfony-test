<?php

namespace Duo\PagePartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractPagePartType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('_type', HiddenType::class, [
				'mapped' => false,
				'data' => md5(static::class)
			])
			->add('value', TextType::class, [
				'label' => false,
				'required' => false
			])
			->add('weight', HiddenType::class, [
				'required' => false
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_page_part';
	}
}