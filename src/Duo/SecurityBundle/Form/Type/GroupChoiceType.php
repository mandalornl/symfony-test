<?php

namespace Duo\SecurityBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Duo\SecurityBundle\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class GroupChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * GroupChoiceType constructor
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_security.form.group_choice.label',
			'class' => Group::class,
			'empty_data' => null,
			'multiple' => true,
			'query_builder' => function(EntityRepository $repository)
			{
				return $repository->createQueryBuilder('e')
					->orderBy('e.name', 'ASC');
			},
			'choice_label' => 'name',
			'attr' => [
				'data-placeholder' => $this->translator->trans('duo_security.form.group_choice.placeholder')
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return EntityType::class;
	}
}
