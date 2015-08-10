<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountLoginType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('_username', 'email', array('label' => 'label.email'))
			->add('_password', 'password', array('label' => 'label.password'))
			->add('Login', 'submit')
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => true,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_account_login_item',
		));
	}

	public function getName()
	{
		return 'ecommerce_account_login';
	}

}
