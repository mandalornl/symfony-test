<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\AdminBundle\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class TranslationsTabType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('translations', TranslationType::class, [
			'entry_type' => PageTranslationType::class,
			'entry_options' => [
				'isNew' => !(is_object($options['data']) && $options['data']->getId() !== null)
			],
			'constraints' => [
				new Valid()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo.page.tab.translations',
			'data' => null
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}