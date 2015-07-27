<?php

namespace Ecommerce\Controller;

use Ecommerce\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AccountController extends Controller
{
	/*
	 * Resume informations on an account
	 */
	public function indexAction()
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		$view = array(
			'customer' => $this->getUser()->toArray(),
			'head_title' => 'Account Index',
		);

		return $this->render('account/index.html.twig', $view);
	}

	/*
	 * Create a new account on POST
	 */
	public function createAction(Request $request)
	{
		$create_form = $this->createForm(
			new Type\AccountCreateType(),
			array('prefix' => 'f'),
			array(
				'action' => $this->generateUrl('account_create'),
				'method' => 'POST',
			)
		);

		$create_form->handleRequest($request);

		if($request->isMethod('POST') and $create_form->isValid())
		{
			$form_data = $create_form->getData();
			$customer_repository = $this->getDoctrine()->getRepository('Ecommerce:Customer');
			$encoder = $this->container->get('security.password_encoder');

			//1 - Check if this email already exists

			$customer = $customer_repository->findOneByEmail($form_data['email']);
			if(!empty($customer))
			{
				if($encoder->isPasswordValid($customer, $form_data['password']))
				{
					$token = new UsernamePasswordToken($customer, $customer->getPassword(), 'secured_area', $customer->getRoles());
					$this->container->get('security.context')->setToken($token);
					return $this->redirectToRoute('account');
				}

				//success (green) - info (blue) - warning (orange) - danger (red)
				$this->addFlash('danger', 'This email already exists');

				return $this->redirectToRoute('account_create');
			}

			//2 - Create a new Customer row

			$customer = new \Ecommerce\Entity\Customer();
			$customer->setEmail($form_data['email']);

			$encoded = $encoder->encodePassword($customer, $form_data['password']);
			$customer->setPassword($encoded);

			$customer_group_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerGroup');
			$group = $customer_group_repository->getDefaultGroup();
			$customer->setGroup($group);

			$date = new \DateTime();
			$customer->setCreatedAt($date);
			$customer->setUpdatedAt($date);

			$customer->setIsActive(TRUE);

			//3 - Create the new customer row in the database

			$em = $this->getDoctrine()->getManager();
			$em->persist($customer);
			$em->flush();

			//4 - Save details

			$customer_details = new \Ecommerce\Entity\CustomerDetails();
			$customer_details->setCustomer($customer);
			$customer_details->setPrefix($form_data['prefix']);
			$customer_details->setFirstname($form_data['firstname']);
			$customer_details->setLastname($form_data['lastname']);
			$customer_details->setLocale('fr_FR');
			$customer_details->setCguValidatedAt($date);

			$em->persist($customer_details);
			$em->flush();

			//5 - Login

			$token = new UsernamePasswordToken($customer, $customer->getPassword(), 'secured_area', $customer->getRoles());
			$this->container->get('security.context')->setToken($token);

			return $this->redirectToRoute('account');
		}

		$view = array(
			'head_title' => 'Account Create',
			'create_form' => $create_form->createView(),
		);

		return $this->render('account/create.html.twig', $view);
	}

	/*
	 * Edit name's data of an account on POST
	 */
	public function editDetailsAction()
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

	}

	/*
	 * Edit email's data of an account on POST
	 */
	public function editEmailAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

	}

	/*
	 * Edit password's data of an account on POST
	 */
	public function editPasswordAction()
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		//on success

		$user = new AppBundle\Entity\User();
		$plainPassword = 'ryanpass';
		$encoder = $this->container->get('security.password_encoder');
		$encoded = $encoder->encodePassword($user, $plainPassword);

		$user->setPassword($encoded);
	}

	/*
	 * Display addresses's data of an account
	 */
	public function addressesAction()
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

	}

	/*
	 * Edit an address's data of an account on POST
	 */
	public function editAddressAction($address_id)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

	}

	/**
	 * Check if the user is logged in
	 * It's easier to write that way
	 * @return boolean
	 */
	private function isLoggedIn()
	{
		return $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
	}
}
