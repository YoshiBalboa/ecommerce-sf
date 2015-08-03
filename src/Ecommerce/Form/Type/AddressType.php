<?php

namespace Ecommerce\Form\Type;

use Ecommerce\Entity\GeoCountry;
use Ecommerce\Entity\GeoCountryRepository;
use Ecommerce\Entity\GeoLocationRepository;
use Ecommerce\Entity\GeoSubdivisionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('prefix', 'e_gender', array('data' => $options['data']['prefix']))
			->add('firstname', 'text', array('label' => 'Firstname:'))
			->add('lastname', 'text', array('label' => 'Lastname:'))
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
			->add('street', 'text', array('label' => 'Street:'))
			->add('telephone', 'text', array(
				'label'	 => 'Telephone:',
				'attr'	 => array(
					'pattern' => '^\+?[0-9][0-9- .]+$',
					'placeholder' => '+1 202-456-1111',
				)
			))
			->add('address_id', 'hidden')
			->add('subdivision', 'hidden')
			->add('location', 'hidden')
			->add('Save', 'submit')
		;

		// PRE_SET_DATA LISTENERS

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function(FormEvent $event)
			{
				$form = $event->getForm();
				$data = $event->getData();

				if(!empty($data['country']))
				{
					//$this->subdivisionModifier($event->getForm(), $data['country']);

					if(!empty($data['subdivision']))
					{
						//$this->locationModifier($event->getForm(), $data['subdivision']);
					}
				}
			}
		);

		// POST_SUBMIT LISTENERS

		$builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)
			{
                // Il est important de récupérer ici $event->getForm()->getData(),
                // car $event->getData() vous renverra la données initiale (vide)
                $country = $event->getForm()->getData();

                // puisque nous avons ajouté l'écouteur à l'enfant, il faudra passer
                // le parent aux fonctions de callback!
                $this->subdivisionModifier($event->getForm()->getParent(), $country);

                $this->locationModifier($event->getForm()->getParent(), $country);
            }
        );

		$builder->get('state')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)
			{
                $subdivision = $event->getForm()->getData();
                $country = $event->getForm()->getParent()->getData()['country'];

                $this->locationModifier($event->getForm()->getParent(), $country, $subdivision);
            }
        );
	}

	/**
	 * Add state field
	 * @param FormInterface $form
	 * @param GeoCountry $country
	 */
	private function subdivisionModifier(FormInterface $form, GeoCountry $country)
	{
		$form->add('state', 'entity', array(
			'label'			 => 'State/Department:',
			'class'			 => 'Ecommerce:GeoSubdivision',
			'property'		 => 'label',
			'query_builder'	 => function(GeoSubdivisionRepository $gsr) use ($country)
			{
				return $gsr->getSubdivisions($country, TRUE);
			},
		));
	}

	/**
	 * Add city field
	 * @param FormInterface $form
	 * @param GeoCountry $country
	 * @param GeoSubdivision $subdivision
	 */
	private function locationModifier(FormInterface $form, GeoCountry $country, GeoSubdivision $subdivision)
	{
		$form->add('city', 'entity', array(
			'label'			 => 'City:',
			'class'			 => 'Ecommerce:GeoLocation',
			'property'		 => 'label',
			'query_builder'	 => function(GeoLocationRepository $glr) use ($country, $subdivision)
			{
				return $glr->getLocations($country, $subdivision, TRUE);
			},
		));
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
		return 'ecommerce_address';
	}
}
