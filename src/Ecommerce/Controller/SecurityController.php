<?php

namespace Ecommerce\Controller;

use Ecommerce\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		$login_form = $this->createForm(
			new Type\AccountLoginType(),
			array('_username' => $lastUsername),
			array(
				'action' => $this->generateUrl('login_check'),
				'method' => 'POST'
			)
		);

		$view = array(
			'head_title' => 'Account Login',
			'login_form' => $login_form->createView(),
			'error' => $error,
		);

		return $this->render('security/login.html.twig', $view);
	}

	public function loginCheckAction()
	{
		// this controller will not be executed,
		// as the route is handled by the Security system
	}
}