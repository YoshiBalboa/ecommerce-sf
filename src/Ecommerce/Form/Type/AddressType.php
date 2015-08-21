<?php

namespace Ecommerce\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Ecommerce\Entity\GeoCountryRepository;
use Ecommerce\Form\DataTransformer\GeoLocationToNumberTransformer;
use Ecommerce\Form\DataTransformer\GeoSubdivisionToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
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
		$subdivision_transformer = new GeoSubdivisionToNumberTransformer($this->om);
		$location_transformer = new GeoLocationToNumberTransformer($this->om);

		$builder
			->add('prefix', 'e_gender', array(
				'data' => $options['data']['prefix']))
			->add('firstname', 'text', array(
				'label' => 'label.firstname'))
			->add('lastname', 'text', array(
				'label' => 'label.lastname'))
			->add('country', 'entity', array(
				'label'			 => 'label.country',
				'class'			 => 'Ecommerce:GeoCountry',
				'property'		 => 'label',
				'query_builder'	 => function(GeoCountryRepository $gcr)
				{
					return $gcr->getCountries(TRUE);
				},
			))
			->add('state', 'text', array(
				'label' => 'label.state',
			))
			->add('postcode', 'text', array(
				'label' => 'label.postcode',
			))
			->add('city', 'text', array(
				'label' => 'label.city',
			))
			->add('street', 'text', array(
				'label' => 'label.street'))
			->add('telephone', 'text', array(
				'label'	 => 'label.telephone',
				'attr'	 => array(
					'pattern'		 => '^\+?[0-9][0-9- .]+$',
					'placeholder'	 => '+1 202-456-1111',
				)
			))
			->add('default_billing', 'choice', array(
				'choices'	 => array('1' => 'label.default-billing'),
				'label'		 => FALSE,
				'multiple'	 => TRUE,
				'expanded'	 => TRUE,
			))
			->add('default_shipping', 'choice', array(
				'choices'	 => array('1' => 'label.default-shipping'),
				'label'		 => FALSE,
				'multiple'	 => TRUE,
				'expanded'	 => TRUE,
			))
			->add('address_id', 'hidden', array('attr' => array('required' => TRUE)))
			->add(
				$builder->create('subdivision', 'hidden', array('attr' => array('required' => TRUE)))
				->addModelTransformer($subdivision_transformer)
			)
			->add(
				$builder->create('location', 'hidden', array('attr' => array('required' => TRUE)))
				->addModelTransformer($location_transformer))
			->add('Save', 'submit', array(
				'label' => 'button.save',))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection'	 => true,
			'csrf_field_name'	 => '_token',
			'intention'			 => 'ecommerce_address_item',
		));
	}

	public function getName()
	{
		return 'e_address';
	}

}
