<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType
{

	private $genderChoices;

	public function __construct(array $genderChoices)
	{
		$this->genderChoices = $genderChoices;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'choices'			 => $this->genderChoices,
			'multiple'			 => FALSE,
			'expanded'			 => TRUE,
			'preferred_choices'	 => array('f')
		));
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'e_gender';
	}

}
