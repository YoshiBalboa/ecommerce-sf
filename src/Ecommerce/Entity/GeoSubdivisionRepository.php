<?php

namespace Ecommerce\Entity;

use Ecommerce\Entity\GeoCountry;
use Doctrine\ORM\EntityRepository;

class GeoSubdivisionRepository extends EntityRepository
{

	public function getSubdivisions(GeoCountry $country, $return_builder = FALSE)
	{
		$subdivisions = $this->createQueryBuilder('gs')
			->where('gs.country = :country')
			->setParameter('country', $country)
			->orderBy('gs.label', 'ASC');

		return $return_builder ? $subdivisions : $subdivisions->getQuery()->getResult();
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
