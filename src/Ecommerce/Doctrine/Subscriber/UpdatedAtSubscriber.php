<?php

namespace Ecommerce\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Class UpdatedAtSubscriber
 */
class UpdatedAtSubscriber implements EventSubscriber
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

		if(!$this->isInWhitelist($entity))
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

		if(!$this->isInWhitelist($entity))
		{
			return;
		}

		$entity->setUpdatedAt(new \DateTime());
	}

	private function isInWhitelist($entity)
	{
		$return = FALSE;

		if($entity instanceof \Ecommerce\Entity\AttributeValue
			or $entity instanceof \Ecommerce\Entity\Category
			or $entity instanceof \Ecommerce\Entity\Customer
			or $entity instanceof \Ecommerce\Entity\CustomerAddress)
		{
			$return = TRUE;
		}

		return $return;
	}

}
