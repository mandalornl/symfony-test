<?php

namespace Duo\SecurityBundle\Form;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('profile', TabType::class, [
					'label' => 'duo.security.tab.profile'
				])
				->add('name', TextType::class, [
					'label' => 'duo.security.form.user.name.label'
				])
				->add('email', EmailType::class, [
					'label' => 'duo.security.form.user.email.label',
					'attr' => [
						'autocomplete' => 'off'
					]
				])
				->add('username', TextType::class, [
					'label' => 'duo.security.form.user.username.label',
					'required' => false,
					'disabled' => true,
					'attr' => [
						'autocomplete' => 'off'
					]
				])
				->add('plainPassword', PasswordType::class, [
					'label' => 'duo.security.form.user.password.label',
					'required' => false,
					'attr' => [
						'autocomplete' => 'off'
					]
				])
			)
			->add(
				$builder->create('properties', TabType::class, [
					'label' => 'duo.security.tab.properties'
				])
				->add('active', CheckboxType::class, [
					'label' => 'duo.security.form.user.active.label'
				])
				->add('groups', GroupChoiceType::class, [
					'label' => 'duo.security.form.user.groups.label'
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => UserInterface::class
		]);
	}
}