<?php

namespace Duo\SeoBundle\Form\Listing;

use Duo\SeoBundle\Entity\Robot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RobotType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('content', TextareaType::class, [
			'label' => 'Robots.txt',
			'attr' => [
				'rows' => 6
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Robot::class
		]);
	}
}
