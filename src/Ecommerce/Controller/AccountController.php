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

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$view = array(
			'customer'	 => $customer_details,
			'head_title' => $this->get('translator')->trans('head_title.account.index'),
			'h1_title' => $this->get('translator')->trans('h1_title.account.index'),
		);

		return $this->render('account/index.html.twig', $view);
	}

	/*
	 * Create a new account on POST
	 */
	public function createAction(Request $request)
	{
		$initial_data = array('prefix' => 'f');

		$form_attributes = array(
			'action' => $this->generateUrl('account_create'),
			'method' => 'POST',
		);

		$create_form = $this->createForm(
			new Type\AccountCreateType(), $initial_data, $form_attributes
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
					$this->updateSecurityToken($customer);
					return $this->redirectToRoute('account');
				}

				$this->addFlash('danger', $this->get('translator')->trans('flash.email-exists'));

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
			$customer_details->setCguValidatedAt(new \DateTime());

			$em->persist($customer_details);
			$em->flush();

			//5 - Login

			$this->updateSecurityToken($customer);

			return $this->redirectToRoute('account');
		}

		$view = array(
			'head_title'	 => $this->get('translator')->trans('head_title.account.create'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.account.create'),
			'create_form'	 => $create_form->createView(),
		);

		return $this->render('account/create.html.twig', $view);
	}

	/*
	 * Edit name's data of an account on POST
	 */
	public function editDetailsAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
		$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

		$initial_data = array(
			'prefix'	 => $customer_details->getPrefix(),
			'firstname'	 => $customer_details->getFirstname(),
			'lastname'	 => $customer_details->getLastname(),
			'birthday'	 => $customer_details->getBirthday(),
		);

		$form_attributes = array(
			'action' => $this->generateUrl('account_edit_details'),
			'method' => 'POST',
		);

		$details_form = $this->createFormBuilder($initial_data, $form_attributes)
			->add('prefix', 'e_gender')
			->add('firstname', 'text', array('label' => 'label.firstname'))
			->add('lastname', 'text', array('label' => 'label.lastname'))
			->add('birthday', 'birthday', array(
				'label'	 => 'label.birthday',
				'format' => 'yyyy-MMMM-dd',
			))
			->add('save', 'submit', array('label' => 'button.save'))
			->getForm();

		$details_form->handleRequest($request);

		if($request->isMethod('POST') and $details_form->isValid())
		{
			$form_data = $details_form->getData();

			$customer_details->setPrefix($form_data['prefix']);
			$customer_details->setFirstname($form_data['firstname']);
			$customer_details->setLastname($form_data['lastname']);
			$customer_details->setBirthday($form_data['birthday']);

			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->addFlash('success', $this->get('translator')->trans('flash.details-saved'));

			return $this->redirectToRoute('account_edit_details');
		}

		$view = array(
			'head_title'	 => $this->get('translator')->trans('head_title.account.edit-details'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.account.edit-details'),
			'details_form'	 => $details_form->createView(),
		);

		return $this->render('account/edit_details.html.twig', $view);
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

		$form_attributes = array(
			'action' => $this->generateUrl('account_edit_email'),
			'method' => 'POST',
			'attr'	 => array('autocomplete' => 'off'),
		);

		$email_form = $this->createFormBuilder(array(), $form_attributes)
			->add('email', 'email', array('label' => 'label.new-email'))
			->add('password', 'e_password')
			->add('save', 'submit', array('label' => 'button.save'))
			->getForm();

		$email_form->handleRequest($request);

		if($request->isMethod('POST') and $email_form->isValid())
		{
			$form_data = $email_form->getData();
			$encoder = $this->container->get('security.password_encoder');

			if(!$encoder->isPasswordValid($this->getUser(), $form_data['password']))
			{
				$this->addFlash('danger', $this->get('translator')->trans('flash.invalid-password'));
				return $this->redirectToRoute('account_edit_email');
			}

			$this->getUser()->setEmail($form_data['email']);

			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->updateSecurityToken($this->getUser());

			$this->addFlash('success', $this->get('translator')->trans('flash.email-saved'));

			return $this->redirectToRoute('account_edit_email');
		}

		$view = array(
			'head_title'	 => $this->get('translator')->trans('head_title.account.edit-email'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.account.edit-email'),
			'email_form'	 => $email_form->createView(),
			'current_email'	 => $this->getUser()->getEmail(),
		);

		return $this->render('account/edit_email.html.twig', $view);
	}

	/*
	 * Edit password's data of an account on POST
	 */
	public function editPasswordAction(Request $request)
	{
		if(!$this->isLoggedIn())
		{
			return $this->redirectToRoute('login');
		}

		$form_attributes = array(
			'action' => $this->generateUrl('account_edit_password'),
			'method' => 'POST',
			'attr'	 => array('autocomplete' => 'off'),
		);

		$password_form = $this->createFormBuilder(array(), $form_attributes)
			->add('new_password', 'password', array(
				'label'			 => 'label.new-password',
				'constraints'	 => array(
					new \Symfony\Component\Validator\Constraints\NotBlank(),
					new \Symfony\Component\Validator\Constraints\Length(array('min' => 6))
				)
			))
			->add('password', 'e_password', array(
				'first_options'	 => array('label' => 'label.current-password'),
				'second_options' => array('label' => 'label.current-password-confirm'),
			))
			->add('save', 'submit', array('label' => 'button.save'))
			->getForm();

		$password_form->handleRequest($request);

		if($request->isMethod('POST') and $password_form->isValid())
		{
			$form_data = $password_form->getData();
			$encoder = $this->container->get('security.password_encoder');

			if(!$encoder->isPasswordValid($this->getUser(), $form_data['password']))
			{
				$this->addFlash('danger', $this->get('translator')->trans('flash.invalid-password'));
				return $this->redirectToRoute('account_edit_password');
			}

			$encoded = $encoder->encodePassword($this->getUser(), $form_data['new_password']);
			$this->getUser()->setPassword($encoded);

			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->updateSecurityToken($this->getUser());

			$this->addFlash('success', $this->get('translator')->trans('flash.new-password-saved'));

			return $this->redirectToRoute('account_edit_password');
		}

		$view = array(
			'head_title'	 => $this->get('translator')->trans('head_title.account.edit-password'),
			'h1_title'		 => $this->get('translator')->trans('h1_title.account.edit-password'),
			'password_form'	 => $password_form->createView(),
		);

		return $this->render('account/edit_password.html.twig', $view);
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

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.account.addresses'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.account.addresses'),
			'addresses' => array(),
		);

		$address_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddress');
		$active_addresses = $address_repository->findBy(
			array('customer' => $this->getUser(), 'isActive' => TRUE));

		if(!empty($active_addresses))
		{
			$customer_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerDetails');
			$customer_details = $customer_details_repository->findOneByCustomer($this->getUser());

			$address_details_repository = $this->getDoctrine()->getRepository('Ecommerce:CustomerAddressDetails');

			$addresses = array();
			foreach($active_addresses as $address)
			{
				$addresses['all'][$address->getAddressId()] = $address_details_repository->getParts($address);
			}

			$addresses['billing'] = $addresses['all'][$customer_details->getDefaultBilling()->getAddressId()];
			$addresses['shipping'] = $addresses['all'][$customer_details->getDefaultShipping()->getAddressId()];

			$view['addresses'] = $addresses;
		}

		return $this->render('account/addresses.html.twig', $view);
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

	/**
	 * Update security token
	 * Enable login/relogin after email or password modification
	 *
	 * @param \Ecommerce\Entity\Customer $customer
	 */
	private function updateSecurityToken(\Ecommerce\Entity\Customer $customer)
	{
		$token = new UsernamePasswordToken($customer, $customer->getPassword(), 'secured_area', $customer->getRoles());
		$this->container->get('security.token_storage')->setToken($token);
	}

}
