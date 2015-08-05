<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;

class CustomerAddressDetailsRepository extends EntityRepository
{

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
