<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountCreateType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('prefix', 'e_gender', array('data' => $options['data']['prefix']))
			->add('firstname', 'text', array('label' => 'label.firstname'))
			->add('lastname', 'text', array('label' => 'label.lastname'))
			->add('email', 'email', array('label' => 'label.email'))
			->add('password', 'e_password')
			->add('Create', 'submit', array(
				'label' => 'button.create',))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => true,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_account_create_item',
		));
	}

	public function getName()
	{
		return 'e_account_create';
	}

}
