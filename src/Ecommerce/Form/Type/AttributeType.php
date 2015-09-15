<?php

namespace Ecommerce\Form\Type;

use Ecommerce\Entity\Repository\AttributeTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('type', 'entity', array(
				'label'			 => 'label.attribute-type',
				'class'			 => 'Ecommerce:AttributeType',
				'property'		 => 'code',
				'query_builder'	 => function(AttributeTypeRepository $atr)
				{
					return $atr->getTypes(TRUE);
				},
			))
			->add('value_fr', 'text', array('label' => 'label.french-label'))
			->add('value_en', 'text', array('label' => 'label.english-label'))
			->add('save', 'submit', array('label' => 'button.save'))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => true,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_attribute_item',
		));
	}

	public function getName()
	{
		return 'e_attribute';
	}

}
