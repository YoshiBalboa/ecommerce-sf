<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\Customer;

class CustomerAddressRepository extends EntityRepository
{

	public function checkAddress($address_id, Customer $customer)
	{
		$address = $this->findOneByAddressId($address_id);
		return (empty($address) or $address->getCustomer()->getCustomerId() != $customer->getCustomerId()) ? NULL : $address;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
