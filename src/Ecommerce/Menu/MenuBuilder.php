<?php

namespace Ecommerce\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\Renderer\ListRenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder
{

	private $factory;
	private $request_uri;

	/**
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory)
	{
		$this->factory = $factory;
	}

	public function createMainMenu(RequestStack $requestStack, SecurityContext $security)
	{
		$this->request_uri = $requestStack->getCurrentRequest()->getRequestUri();

		//Access services from the container!
		//$em = $this->container->get('doctrine')->getManager();

		$menu = $this->factory->createItem('root');

		//Set root ul attributes
		$menu->setChildrenAttribute('class', 'nav navbar-nav');

		//Home
		$menu->addChild('Home', array('route' => 'home'));

		if(!$security->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			$menu->addChild('Login', array('route' => 'login'));
		}
		else
		{
			//Account and sublevel
			$menu->addChild('Account', array('uri' => '#', 'extras' => array('safe_label' => TRUE)));
			$menu['Account']->setAttribute('class', 'dropdown');
			$menu['Account']->setLinkAttributes(array(
				'href'			 => "#",
				'class'			 => 'dropdown-toggle',
				'data-toggle'	 => 'dropdown',
				'role'			 => 'button',
				'aria-haspopup'	 => 'true',
				'aria-expanded'	 => 'false',
			));

			$menu['Account']->addChild('Details', array('route' => 'account'));
			$menu['Account']->addChild('Addresses', array('route' => 'account_addresses'));
			$menu['Account']->addChild('-', array('attributes' => array('role'	 => 'separator',
					'class'	 => 'divider')));
			$menu['Account']->addChild('Edit Details', array('route' => 'account_edit_details'));
			$menu['Account']->addChild('Edit Email', array('route' => 'account_edit_email'));
			$menu['Account']->addChild('Edit Password', array('route' => 'account_edit_password'));

			$menu['Account']->setChildrenAttribute('class', 'dropdown-menu');
			$menu['Account']->setLabel('Account <span class="caret"></span>');

			//Logout
			$menu->addChild('Logout', array('route' => 'logout'));
		}

		return $menu;
	}

	public function createBreadcrumbsMenu()
	{
		//@TODO
		$menu = $this->factory->createItem('root');

		return $menu;
	}

}
