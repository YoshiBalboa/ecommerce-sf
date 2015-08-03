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
				'label' => 'Firstname:'))
			->add('lastname', 'text', array(
				'label' => 'Lastname:'))
			->add('country', 'entity', array(
				'label'			 => 'Country:',
				'class'			 => 'Ecommerce:GeoCountry',
				'property'		 => 'label',
				'query_builder'	 => function(GeoCountryRepository $gcr)
				{
					return $gcr->getCountries(TRUE);
				},
			))
			->add('state', 'text', array(
				'label' => 'State/Department:',
			))
			->add('postcode', 'text', array(
				'label' => 'Postcode:',
			))
			->add('city', 'text', array(
				'label' => 'City:',
			))
			->add('street', 'text', array(
				'label' => 'Street:'))
			->add('telephone', 'text', array(
				'label'	 => 'Telephone:',
				'attr'	 => array(
					'pattern'		 => '^\+?[0-9][0-9- .]+$',
					'placeholder'	 => '+1 202-456-1111',
				)
			))
			->add('address_id', 'hidden')
			->add(
				$builder->create('subdivision', 'hidden')
				->addModelTransformer($subdivision_transformer)
			)
			->add(
				$builder->create('location', 'hidden')
				->addModelTransformer($location_transformer))
			->add('Save', 'submit')
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