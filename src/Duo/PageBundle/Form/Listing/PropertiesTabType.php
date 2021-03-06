<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\AdminBundle\Form\Type\WeightChoiceType;
use Duo\PageBundle\Form\Type\PageAutoCompleteType;
use Duo\TaxonomyBundle\Form\Type\TaxonomyChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo_page.form.page.name.label',
				'required' => false
			])
			->add('weight', WeightChoiceType::class, [
				'label' => 'duo_page.form.page.weight.label',
				'required' => false
			])
			->add('taxonomies', TaxonomyChoiceType::class, [
				'label' => 'duo_page.form.page.taxonomies.label',
				'required' => false
			])
			->add('parent', PageAutoCompleteType::class, [
				'label' => 'duo_page.form.page.parent.label',
				'required' => false,
				'excludeSelf' => true
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_page.tab.properties'
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
