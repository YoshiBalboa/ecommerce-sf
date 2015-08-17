<?php

namespace Ecommerce\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ecommerce\Entity\Customer;

/**
 * Class CustomerSubscriber
 */
class CustomerSubscriber implements EventSubscriber
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

		if(!($entity instanceof Customer))
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

		if(!($entity instanceof Customer))
		{
			return;
		}

		$entity->setUpdatedAt(new \DateTime());
	}

}
