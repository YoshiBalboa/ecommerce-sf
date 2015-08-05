<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordType extends AbstractType
{

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'type'				 => 'password',
			'invalid_message'	 => 'Passwords do not match',
			'options'			 => array('required' => true),
			'first_options'		 => array('label' => 'Password:'),
			'second_options'	 => array('label' => 'Confirm password:'),
			'constraints'		 => array(
				new NotBlank(),
				new Length(array('min' => 6))
			)
		));
	}

	public function getParent()
	{
		return 'repeated';
	}

	public function getName()
	{
		return 'e_password';
	}

}
