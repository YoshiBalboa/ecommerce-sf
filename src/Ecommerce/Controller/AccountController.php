<?php

namespace Ecommerce\Controller;

use Ecommerce\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

		if($request->isMethod('POST') and false)
		{
			//on success
			$customer = new Ecommerce\Entity\Customer();
			$plainPassword = 'ryanpass';
			$encoder = $this->container->get('security.password_encoder');
			$encoded = $encoder->encodePassword($customer, $plainPassword);

			$customer->setPassword($encoded);
			$customer->flush();

			return $this->redirectToRoute('account');
		}

		$view = array(
			'head_title' => 'Account Create',
			'create_form' => $create_form->createView(),
			'form2' => $create_form->createView(),
		);

		return $this->render('account/create.html.twig', $view);
	}

	/*
	 * Edit email's data of an account on POST
	 */
	public function editEmailAction()
	{

	}

	/*
	 * Edit name's data of an account on POST
	 */
	public function editNameAction()
	{

	}

	/*
	 * Edit password's data of an account on POST
	 */
	public function editPasswordAction()
	{

		//on success

		$user = new AppBundle\Entity\User();
		$plainPassword = 'ryanpass';
		$encoder = $this->container->get('security.password_encoder');
		$encoded = $encoder->encodePassword($user, $plainPassword);

		$user->setPassword($encoded);
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
