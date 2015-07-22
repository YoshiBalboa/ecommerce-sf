<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
	public function indexAction()
	{
		return $this->render('index/index.html.twig');
	}
}
