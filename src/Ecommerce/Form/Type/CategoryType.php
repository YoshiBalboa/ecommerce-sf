<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('category_fr', 'text', array('label' => 'label.french-label'))
			->add('category_en', 'text', array('label' => 'label.english-label'))
			->add('save', 'submit', array('label' => 'button.save'))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => true,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_category_item',
		));
	}

	public function getName()
	{
		return 'e_category';
	}

}
