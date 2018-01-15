<?php

namespace Duo\PagePartBundle\Form;

use Duo\PagePartBundle\Entity\HeadingPagePart;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeadingPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->add('type', ChoiceType::class, [
			'required' => false,
			'placeholder' => false,
			'choices' => [
				'Heading 1' => 'h1',
				'Heading 2' => 'h2',
				'Heading 3' => 'h3',
				'Heading 4' => 'h4',
				'Heading 5' => 'h5',
				'Heading 6' => 'h6'
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => HeadingPagePart::class,
			'model_class' => HeadingPagePart::class
		]);
	}
}