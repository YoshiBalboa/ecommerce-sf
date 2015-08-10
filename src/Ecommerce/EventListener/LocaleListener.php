<?php

namespace Ecommerce\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{

	protected $container;
	protected $em;
	protected $request;

	public function __construct(EntityManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}

	public function onKernelRequest(GetResponseEvent $event)
	{
		$this->request = $event->getRequest();
		if(!$this->request->hasPreviousSession())
		{
			return;
		}

		// on essaie de voir si la locale a été fixée dans le paramètre de routing _locale
		$locale = $this->request->attributes->get('_locale');
		if(!empty($locale))
		{
			$this->request->getSession()->set('_locale', $locale);
		}
		else
		{
			// si aucune locale n'a été fixée explicitement dans la requête, on utilise celle de la session
			$this->request->setLocale($this->request->getSession()->get('_locale', $this->container->getParameter('locale')));
		}
	}

	public function onKernelController()
	{
		$this->setSessionLocale();
	}

	public static function getSubscribedEvents()
	{
		return array(
			// doit être enregistré avant le Locale listener par défaut
			KernelEvents::REQUEST	 => array(array('onKernelRequest', 17)),
			KernelEvents::CONTROLLER => array(array('onKernelController', 17)),
		);
	}

	/**
	 * Reset the session's locale for the next request
	 */
	private function setSessionLocale()
	{
		if(!empty($this->container->get('security.token_storage')->getToken())
			and $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			$customer_details_repository = $this->em->getRepository('Ecommerce:CustomerDetails');
			$customer_details = $customer_details_repository->findOneByCustomer($this->container->get('security.token_storage')->getToken()->getUser());

			$this->request->getSession()->set('_locale', $customer_details->getLocale());
		}
	}

}
