<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\GeoCountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class AddressController extends Controller
{

	/*
	 * Create an address on POST
	 */
	public function createAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			//return $this->redirectToRoute('login');
		}

		$country_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoCountry');
		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$initial_data = array(
			'prefix'	 => $customer_details->getPrefix(),
			'firstname'	 => $customer_details->getFirstname(),
			'lastname'	 => $customer_details->getLastname(),
			'country'	 => $country_repository->findOneById(GeoCountryRepository::COUNTRY_FRANCE),
		);

		$form_attributes = array(
			'action' => $this->generateUrl('address_create'),
			'method' => 'POST',
		);

		$address_form = $this->createForm(
			'e_address', $initial_data, $form_attributes
		);

		$address_form->handleRequest($request);

		if($request->isMethod('POST') and $address_form->isValid())
		{
			$address = $this->saveAddress($address_form);

			if(!empty($address))
			{
				$this->addFlash('success', $this->get('translator')->trans('flash.new-address-saved'));
				return $this->redirectToRoute('account_addresses');
			}

			$this->addFlash('danger', $this->get('translator')->trans('flash.error-try-again'));
		}

		$view = array(
			'head_title'	 => $this->get('translator')->trans('head_title.address.create'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.address.create'),
			'address_form'	 => $address_form->createView(),
		);

		return $this->render('address/default.html.twig', $view);
	}

	/*
	 * Edit an address on POST
	 */
	public function editAction(Request $request, $address_id)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.address.edit'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.address.edit'),
		);

		return $this->render('address/default.html.twig', $view);
	}

	/**
	 * Retrieve subdivisions for a given country
	 * AJAX action
	 */
	public function subdivisionsAction(Request $request)
	{
		if(!$request->isXmlHttpRequest() or empty($request->request->get('country_id')))
		{
			return new JsonResponse(array('error' => 'Invalid request'));
		}

		$country_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoCountry');
		$subdivision_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoSubdivision');

		$country = $country_repository->findOneById($request->request->get('country_id'));
		if(empty($country))
		{
			return new JsonResponse(array('error' => 'Invalid country'));
		}

		$subdivisions = $subdivision_repository->getSubdivisions($country);

		if(empty($subdivisions))
		{
			return new JsonResponse(array('success' => 'No subdivision'));
		}

		$encoders = array(new JsonEncoder());
		$normalizers = array(new GetSetMethodNormalizer());
		$serializer = new Serializer($normalizers, $encoders);

		$response = new Response();
		$response->setContent($serializer->serialize($subdivisions, 'json'));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * Retrieve locations for a given set country + subdivision
	 * AJAX action
	 */
	public function locationsAction(Request $request)
	{
		if(!$request->isXmlHttpRequest() or empty($request->request->get('country_id')))
		{
			return new JsonResponse(array('error' => 'Invalid request'));
		}

		$country_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoCountry');
		$location_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoLocation');

		$country = $country_repository->findOneById($request->request->get('country_id'));
		if(empty($country))
		{
			return new JsonResponse(array('error' => 'Invalid country'));
		}

		$subdivision = '';
		if(!empty($request->request->get('subdivision_id')))
		{
			$subdivision_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoSubdivision');
			$subdivision = $subdivision_repository->findOneById($request->request->get('subdivision_id'));
		}

		$locations = $location_repository->getLocations($country, $subdivision);

		$encoders = array(new JsonEncoder());
		$normalizers = array(new GetSetMethodNormalizer());
		$serializer = new Serializer($normalizers, $encoders);

		$response = new Response();
		$response->setContent($serializer->serialize($locations, 'json'));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * Add or update an address after the POST of a valid address form
	 * @param Form $form
	 * @return boolean
	 */
	private function saveAddress(Form $form)
	{
		$data = $form->getData();
		$date = new \DateTime();

		//1 - Look for the existing address
		if(!empty($data['address_id']))
		{
			$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
			$address = $address_repository->checkAddress($data['address_id'], $this->getUser());
		}

		//2 - If no address was found, create a new CustomerAddress row
		if(empty($address))
		{
			$address = new \Ecommerce\Entity\CustomerAddress();
			$address->setCustomer($this->getUser());
			$address->setCreatedAt($date);
			$address->setIsActive(TRUE);
		}

		$address->setUpdatedAt($date);

		//3 - Create the new customer_address row in the database

		$em = $this->getDoctrine()->getManager();
		$em->persist($address);
		$em->flush();

		//4 - Save details

		$address_details = new \Ecommerce\Entity\CustomerAddressDetails();
		$address_details->setAddress($address);
		$address_details->setPrefix($data['prefix']);
		$address_details->setFirstname($data['firstname']);
		$address_details->setLastname($data['lastname']);
		$address_details->setStreet($data['street']);
		$address_details->setPostcode($data['postcode']);
		$address_details->setLocation($data['location']);
		$address_details->setSubdivision($data['subdivision']);
		$address_details->setCountry($data['country']);
		$address_details->setTelephone($data['telephone']);

		$em->persist($address_details);
		$em->flush();

		return $address_details->getAddress();
	}

	/**
	 * Check if the user is logged in
	 * It's easier to write that way
	 * @return boolean
	 */
	private function isLoggedIn()
	{
		if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			return TRUE;
		}
		else
		{
			$this->addFlash('warning', $this->get('translator')->trans('flash.login2proceed'));
			return FALSE;
		}
	}

}
