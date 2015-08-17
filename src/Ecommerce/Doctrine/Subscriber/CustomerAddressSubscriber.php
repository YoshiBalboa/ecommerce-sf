<?php

namespace Ecommerce\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ecommerce\Entity\CustomerAddress;

/**
 * Class CustomerAddressSubscriber
 */
class CustomerAddressSubscriber implements EventSubscriber
{

	public function getSubscribedEvents()
	{
		return array(
			//	Events::preRemove,
			//	Events::postRemove,
			Events::prePersist,
			//	Events::postPersist,
			Events::preUpdate,
			//	Events::postUpdate,
			//	Events::postLoad,
			//	Events::loadClassMetadata,
			//	Events::onClassMetadataNotFound,
			//	Events::preFlush,
			//	Events::onFlush,
			//	Events::onClear,
		);
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if(!($entity instanceof CustomerAddress))
		{
			return;
		}

		$date = new \DateTime();
		$entity->setCreatedAt($date);
		$entity->setUpdatedAt($date);
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if(!($entity instanceof CustomerAddress))
		{
			return;
		}

		$entity->setUpdatedAt(new \DateTime());
	}

}
