<?php

namespace Ecommerce\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListener
{

	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		if($event->getAuthenticationToken()->isAuthenticated())
		{
			$customer_details_repository = $this->em->getRepository('Ecommerce:CustomerDetails');
			$customer_details = $customer_details_repository->findOneByCustomer($event->getAuthenticationToken()->getUser());

			$event->getRequest()->getSession()->set('_locale', $customer_details->getLocale());
		}
	}

}
