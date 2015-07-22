<?php

namespace Ecommerce\Form\Type;

use Ecommerce\Form\Type\GenderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountCreateType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('prefix', 'gender', array('data' => $options['data']['prefix']))
			->add('firstname', 'text', array('label' => 'Firstname:'))
			->add('lastname', 'text', array('label' => 'Lastname:'))
			->add('_username', 'email', array('label' => 'Email:'))
			->add('_password', 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'Passwords do not match',
				'options' => array('required' => true),
				'first_options'  => array('label' => 'Password:'),
				'second_options' => array('label' => 'Confirm password:'),
			))
			->add('Create', 'submit')
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	=> true,
			'csrf_field_name'	=> '_token',
			'intention'			=> 'ecommerce_account_create_item',
		));
	}

	public function getName()
	{
		return 'ecommerce_account_create';
	}
}

