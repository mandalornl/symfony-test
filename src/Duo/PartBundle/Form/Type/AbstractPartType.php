<?php

namespace Duo\PartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPartType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('_type', HiddenType::class, [
				'mapped' => false,
				'data' => md5(static::class)
			])
			->add('weight', HiddenType::class)
			->add('section', HiddenType::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_part';
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => $this->getDataClass(),
			'model_class' => $this->getDataClass()
		]);
	}

	/**
	 * Get data class
	 *
	 * @return string
	 */
	abstract protected function getDataClass(): string;
}
