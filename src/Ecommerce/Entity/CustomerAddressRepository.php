<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\Customer;

class CustomerAddressRepository extends EntityRepository
{

	public function checkAddress($address_id, Customer $customer)
	{
		$address = $this->createQueryBuilder('a')
			->where('a.address_id = :address_id AND a.customer = :customer')
			->setParameters(array(
				'address_id' => $address_id,
				'customer'	 => $customer))
			->getQuery()
			->getOneOrNullResult();

		return $address;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
