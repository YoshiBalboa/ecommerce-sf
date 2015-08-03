<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\GeoCountryRepository;
use Ecommerce\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
		//$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$customer_repository = $this->getDoctrine()->getRepository('Ecommerce:Customer');
		$tmp_customer = $customer_repository->loadUserByUsername('bouyouyouyou@gmail.com');
		$customer_details = $customer_details_repository->findOneByCustomer($tmp_customer);

		$initial_data = array(
			'prefix' => $customer_details->getPrefix(),
			'firstname' => $customer_details->getFirstname(),
			'lastname' => $customer_details->getLastname(),
			'country' => $country_repository->findOneById(GeoCountryRepository::COUNTRY_FRANCE),
		);

		$form_attributes = array(
			'action' => $this->generateUrl('address_create'),
			'method' => 'POST',
		);

		$address_form = $this->createForm(
			new Type\AddressType(),
			$initial_data,
			$form_attributes
		);

		$address_form->handleRequest($request);

		if($request->isMethod('POST') and $address_form->isValid())
		{
			die('Line:' . __LINE__);
		}

		$view = array(
			'head_title' => 'Address Create',
			'h1_title' => 'Create an address',
			'address_form' => $address_form->createView(),
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
			'head_title' => 'Address Edit',
			'h1_title' => 'Edit an address',
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
			$this->addFlash('warning', 'You must login to proceed');
			return FALSE;
		}
	}
}