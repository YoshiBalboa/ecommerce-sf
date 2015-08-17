<?php

namespace Ecommerce\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ecommerce\Entity\CustomerAddressDetails;

/**
 * Class CustomerAddressDetailsSubscriber
 */
class CustomerAddressDetailsSubscriber implements EventSubscriber
{

	public function getSubscribedEvents()
	{
		return array(
			//	Events::preRemove,
			//	Events::postRemove,
			//	Events::prePersist,
			Events::postPersist,
			//	Events::preUpdate,
			Events::postUpdate,
			//	Events::postLoad,
			//	Events::loadClassMetadata,
			//	Events::onClassMetadataNotFound,
			//	Events::preFlush,
			//	Events::onFlush,
			//	Events::onClear,
		);
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		if(!($args->getEntity() instanceof CustomerAddressDetails))
		{
			return;
		}

		$this->setCustomerAddressUpdatedAt($args);
	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		if(!($args->getEntity() instanceof CustomerAddressDetails))
		{
			return;
		}

		$this->setCustomerAddressUpdatedAt($args);
	}

	private function setCustomerAddressUpdatedAt($args)
	{
		$address = $args->getEntity()->getAddress();
		$address->setUpdatedAt(new \DateTime());

		$em = $args->getEntityManager();
		$em->persist($address);
		$em->flush();
	}

}
