<?php

namespace Ecommerce\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuVoter implements VoterInterface
{
	/**
	 * @var SymfonyComponentDependencyInjectionContainerInterface
	 */
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Checks whether an item is current.
	 *
	 * If the voter is not able to determine a result,
	 * it should return null to let other voters do the job.
	 *
	 * @param ItemInterface $item
	 * @return boolean|null
	 */
	public function matchItem(ItemInterface $item)
	{
		/* @var $request SymfonyComponentHttpFoundationRequest */
		$request = $this->container->get('request');

		if($item->getUri() === $request->getRequestUri())
		{
			return true;
		}

		// C'est ici que l'on vérifie que la route en cours d'utilisation est bien la même
		// que celle contenu dans l'item que nous passe le KnpMenuBundle
		if($item->getExtra('routes') !== null && in_array($request->attributes->get('_route'), $item->getExtra('routes')))
		{
			return true;
		}

		return null;
	}
}
