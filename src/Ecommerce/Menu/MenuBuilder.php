<?php

namespace Ecommerce\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\Renderer\ListRenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MenuBuilder
{

	private $factory;
	private $request_uri;
	private $translator;

	/**
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory, $translator)
	{
		$this->factory = $factory;
		$this->translator = $translator;
	}

	public function createMainMenu(RequestStack $requestStack, AuthorizationChecker $authorization_checker)
	{
		$this->request_uri = $requestStack->getCurrentRequest()->getRequestUri();

		//Access services from the container!
		//$em = $this->container->get('doctrine')->getManager();

		$menu = $this->factory->createItem('root');

		//Set root ul attributes
		$menu->setChildrenAttribute('class', 'nav navbar-nav');

		//Home
		$menu->addChild($this->translator->trans('menu.home'), array('route' => 'home'));

		if(!$authorization_checker->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			$menu->addChild($this->translator->trans('menu.login'), array('route' => 'login'));
			$menu[$this->translator->trans('menu.login')]->setLinkAttribute('id', 'login');
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

			$menu['Account']->addChild($this->translator->trans('menu.account.index'), array(
				'route' => 'account'));
			$menu['Account']->addChild($this->translator->trans('menu.account.addresses'), array(
				'route' => 'account_addresses'));
			$menu['Account']->addChild('-', array('attributes' => array('role'	 => 'separator',
					'class'	 => 'divider')));
			$menu['Account']->addChild($this->translator->trans('menu.account.edit-details'), array(
				'route' => 'account_edit_details'));
			$menu['Account']->addChild($this->translator->trans('menu.account.edit-email'), array(
				'route' => 'account_edit_email'));
			$menu['Account']->addChild($this->translator->trans('menu.account.edit-password'), array(
				'route' => 'account_edit_password'));

			$menu['Account']->setChildrenAttribute('class', 'dropdown-menu');
			$menu['Account']->setLabel($this->translator->trans('menu.account.base') . ' <span class="caret"></span>');

			if($authorization_checker->isGranted('ROLE_ADMIN'))
			{
				$menu->addChild('Attribute', array('uri' => '#', 'extras' => array('safe_label' => TRUE)));
				$menu['Attribute']->setAttribute('class', 'dropdown');
				$menu['Attribute']->setLinkAttributes(array(
					'href'			 => "#",
					'class'			 => 'dropdown-toggle',
					'data-toggle'	 => 'dropdown',
					'role'			 => 'button',
					'aria-haspopup'	 => 'true',
					'aria-expanded'	 => 'false',
				));

				$menu['Attribute']->addChild($this->translator->trans('menu.attribute.manage-category'), array(
					'route' => 'attribute_display_category'));

				$menu['Attribute']->addChild($this->translator->trans('menu.attribute.manage-subcategory'), array(
					'route' => 'attribute_display_subcategory'));

				$menu['Attribute']->addChild($this->translator->trans('menu.attribute.manage-attribute'), array(
					'route' => 'attribute_display_type', 'routeParameters' => array('type_id' => 0)));

				$menu['Attribute']->setChildrenAttribute('class', 'dropdown-menu');
				$menu['Attribute']->setLabel($this->translator->trans('menu.attribute.base') . ' <span class="caret"></span>');
			}

			//Logout
			$menu->addChild($this->translator->trans('menu.logout'), array('route' => 'logout'));
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
