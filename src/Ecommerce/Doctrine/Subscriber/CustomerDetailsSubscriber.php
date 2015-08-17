<?php

namespace Ecommerce\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ecommerce\Entity\CustomerDetails;

/**
 * Class CustomerDetailsSubscriber
 */
class CustomerDetailsSubscriber implements EventSubscriber
{

	public function getSubscribedEvents()
	{
		return array(
			//	Events::preRemove,
			//	Events::postRemove,
			//	Events::prePersist,
			//	Events::postPersist,
			//	Events::preUpdate,
			Events::postUpdate,
			Events::postLoad,
			//	Events::loadClassMetadata,
			//	Events::onClassMetadataNotFound,
			//	Events::preFlush,
			//	Events::onFlush,
			//	Events::onClear,
		);
	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		if(!($args->getEntity() instanceof CustomerDetails))
		{
			return;
		}

		$customer = $args->getEntity()->getCustomer();
		$customer->setUpdatedAt(new \DateTime());

		$em = $args->getEntityManager();
		$em->persist($customer);
		$em->flush();
	}

	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$em = $args->getEntityManager();

		if(!($entity instanceof CustomerDetails))
		{
			return;
		}

		//Everytime we load customer details, validate the default addresses
		$customer_details_repository = $em->getRepository('Ecommerce:CustomerDetails');
		$customer_details_repository->checkDefaultAddresses($entity->getCustomer());
	}

}
