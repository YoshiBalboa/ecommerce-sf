<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;

class GeoCountryRepository extends EntityRepository
{

	const COUNTRY_FRANCE = 75;

	public function getCountries($return_builder = FALSE)
	{
		$countries = $this->createQueryBuilder('gc')
			->orderBy('gc.label', 'ASC');

		if(!$return_builder)
		{
			$countries->getQuery()->execute();
		}

		return $countries;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
