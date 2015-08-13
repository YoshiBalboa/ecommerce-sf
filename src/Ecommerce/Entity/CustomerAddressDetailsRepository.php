<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;

class CustomerAddressDetailsRepository extends EntityRepository
{
	/**
	 * Retrieve all parts of an address as simple type (no more object)
	 * @return Array
	 */
	public function getParts(\Ecommerce\Entity\CustomerAddress $address)
	{
		$address_details = $this->createQueryBuilder('cad')
			->where('cad.address = :address')
			->setParameter('address', $address)
			->getQuery()
			->getOneOrNullResult();

		if(null === $address_details)
		{
			$message = sprintf(
				'Unable to find CustomerAddressDetails object identified by "%s".', $address->getAddressId()
			);

			throw new \Exception($message);
		}

		$parts = array(
			'address_id' => $address_details->getAddress()->getAddressId(),
			'prefix' => $address_details->getPrefix(),
			'firstname' => $address_details->getFirstname(),
			'lastname' => $address_details->getLastname(),
			'country_id' => $address_details->getCountry()->getId(),
			'country' => $address_details->getCountry()->getLabel(),
			'subdivision_id' => empty($address_details->getSubdivision()) ? null : $address_details->getSubdivision()->getId(),
			'subdivision' => empty($address_details->getSubdivision()) ? null : $address_details->getSubdivision()->getLabel(),
			'location_id' => $address_details->getLocation()->getId(),
			'location' => $address_details->getLocation()->getLabel(),
			'street' => $address_details->getStreet(),
			'postcode' => $address_details->getPostcode(),
			'telephone' => $address_details->getTelephone(),
		);

		return $parts;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
