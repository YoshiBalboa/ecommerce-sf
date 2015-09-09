<?php

namespace Ecommerce\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Ecommerce\Form\DataTransformer\CategoryToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubcategoryType extends AbstractType
{

	/**
	 * @var ObjectManager
	 */
	private $om;

	/**
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$category_transformer = new CategoryToNumberTransformer($this->om);
		$builder
			->add(
				$builder->create('category', 'choice', array(
					'label'		 => 'label.category',
					'multiple'	 => FALSE,
					'expanded'	 => FALSE,
					'choices'	 => $options['data']['categories']))
				->addModelTransformer($category_transformer)
			)
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
			'intention'			 => 'ecommerce_subcategory_item',
		));
	}

	public function getName()
	{
		return 'e_subcategory';
	}

}
