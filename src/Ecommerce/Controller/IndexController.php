<?php

namespace Ecommerce\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{

	public function indexAction(Request $request)
	{
		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.index.home'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.index.home'),
			'locale'	 => $request->getLocale(),
		);

		return $this->render('index/index.html.twig', $view);
	}

}
