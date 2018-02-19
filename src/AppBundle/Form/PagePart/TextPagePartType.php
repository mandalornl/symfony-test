<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\TextPagePart;
use Duo\PageBundle\Form\AbstractPagePartType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TextPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->add('value', TextareaType::class, [
			'label' => false,
			'attr' => [
				'rows' => 6
			],
			'constraints' => [
				new NotBlank()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => TextPagePart::class,
			'model_class' => TextPagePart::class
		]);
	}
}