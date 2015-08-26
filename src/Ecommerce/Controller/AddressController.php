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
			return $this->redirectToRoute('login');
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

		$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
		$address = $address_repository->checkAddress($address_id, $this->getUser());

		if(empty($address))
		{
			$this->addFlash('success', $this->get('translator')->trans('flash.invalid-address'));
			return $this->redirectToRoute('account_addresses');
		}

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());
		$address_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddressDetails');
		$address_details = $address_details_repository->findOneByAddress($address);

		$initial_data = array(
			'prefix'			 => $address_details->getPrefix(),
			'firstname'			 => $address_details->getFirstname(),
			'lastname'			 => $address_details->getLastname(),
			'country'			 => $address_details->getCountry(),
			'state'				 => empty($address_details->getSubdivision()) ? '' : $address_details->getSubdivision()->getLabel(),
			'postcode'			 => $address_details->getPostcode(),
			'city'				 => empty($address_details->getLocation()) ? '' : $address_details->getLocation()->getLabel(),
			'street'			 => $address_details->getStreet(),
			'telephone'			 => $address_details->getTelephone(),
			'default_billing'	 => array(1 => ($customer_details->getDefaultBilling()->getAddressId() == $address_id)),
			'default_shipping'	 => array(1 => ($customer_details->getDefaultShipping()->getAddressId() == $address_id)),
			'address_id'		 => $address_id,
			'subdivision'		 => empty($address_details->getSubdivision()) ? NULL : $address_details->getSubdivision(),
			'location'			 => empty($address_details->getLocation()) ? NULL : $address_details->getLocation(),
		);

		$form_attributes = array(
			'action' => $this->generateUrl('address_edit', array('address_id' => $address_id)),
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
			'head_title'	 => $this->get('translator')->trans('head_title.address.edit'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.address.edit'),
			'address_form'	 => $address_form->createView(),
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

		//1 - Validate country + subdivision + location
		$location_repository = $this->getDoctrine()->getRepository('Ecommerce:GeoLocation');
		if(!$location_repository->isValidLocation($data['country'], $data['subdivision'], $data['location']))
		{
			return FALSE;
		}

		//2 - Look for the existing address
		if(!empty($data['address_id']))
		{
			$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
			$address = $address_repository->checkAddress($data['address_id'], $this->getUser());
		}

		//3 - If no address was found, create a new CustomerAddress row
		if(empty($address))
		{
			$address = new \Ecommerce\Entity\CustomerAddress();
			$address->setCustomer($this->getUser());
			$address->setIsActive(TRUE);
		}

		//4 - Create the new customer_address row in the database

		$em = $this->getDoctrine()->getManager();
		$em->persist($address);
		$em->flush();

		//5 - Save details

		$address_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddressDetails');
		$address_details = $address_details_repository->findOneByAddress($address);

		if(empty($address_details))
		{
			$address_details = new \Ecommerce\Entity\CustomerAddressDetails();
		}

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

		//6 - Assign as default address
		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		if(empty($customer_details->getDefaultBilling()) or ! empty($data['default_billing'][0]))
		{
			$customer_details->setDefaultBilling($address);
		}

		if(empty($customer_details->getDefaultShipping()) or ! empty($data['default_shipping'][0]))
		{
			$customer_details->setDefaultShipping($address);
		}

		$em->flush();

		return $address_details->getAddress();
	}

	/*
	 * Display all active addresses on GET
	 * Delete one address on POST
	 */
	public function deleteAction(Request $request, $address_id)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		if(!$request->isMethod('POST'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_METHOD_NOT_ALLOWED, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
		$address = $address_repository->checkAddress($address_id, $this->getUser());

		if(empty($address))
		{
			return new Response($this->get('translator')->trans('flash.invalid-address'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$address->setIsActive(FALSE);
		$em = $this->getDoctrine()->getManager();
		$em->flush();

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details_repository->checkDefaultAddresses($this->getUser());
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$response = array(
			'success'	 => TRUE,
			'billing'	 => empty($customer_details->getDefaultBilling()) ? '' : $customer_details->getDefaultBilling()->getAddressId(),
			'shipping'	 => empty($customer_details->getDefaultShipping()) ? '' : $customer_details->getDefaultShipping()->getAddressId(),
		);

		return new JsonResponse($response);
	}

	/**
	 * Set the given address as default billing address on POST
	 * @param Request $request
	 */
	public function setBillingAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		if(!$request->isMethod('POST'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_METHOD_NOT_ALLOWED, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		if(empty($request->request->get('address_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
		$address = $address_repository->checkAddress($request->request->get('address_id'), $this->getUser());

		if(empty($address))
		{
			return new Response($this->get('translator')->trans('flash.invalid-address'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$customer_details->setDefaultBilling($address);

		$em = $this->getDoctrine()->getManager();
		$em->flush();

		return new JsonResponse(array('success' => TRUE));
	}

	/**
	 * Set the given address as default shipping address on POST
	 * @param Request $request
	 */
	public function setShippingAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		if(!$request->isMethod('POST'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_METHOD_NOT_ALLOWED, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		if(empty($request->request->get('address_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
		$address = $address_repository->checkAddress($request->request->get('address_id'), $this->getUser());

		if(empty($address))
		{
			return new Response($this->get('translator')->trans('flash.invalid-address'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$customer_details->setDefaultShipping($address);

		$em = $this->getDoctrine()->getManager();
		$em->flush();

		return new JsonResponse(array('success' => TRUE));
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
