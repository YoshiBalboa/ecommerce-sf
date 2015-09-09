<?php

namespace Ecommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeValueType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', 'text', array('label' => 'label.label'))
			->add('urlkey', 'text', array('label' => 'label.url'))
			->add('value_id', 'hidden', array('attr' => array('required' => TRUE)))
			->add('type_id', 'hidden', array('attr' => array('required' => TRUE)))
			->add('locale', 'hidden', array('attr' => array('required' => TRUE)))
			->add('save', 'submit', array('label' => 'button.save'))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => false,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_attribute_value_item',
		));
	}

	public function getName()
	{
		return 'e_attr_value';
	}

}
