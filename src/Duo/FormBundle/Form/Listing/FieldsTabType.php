<?php

namespace Duo\FormBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\FormBundle\Form\Type\FormPartCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class FieldsTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('parts', FormPartCollectionType::class, [
			'constraints' => [
				new Valid()
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_form.tab.fields'
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
