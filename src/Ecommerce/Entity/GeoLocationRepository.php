<?php

namespace Ecommerce\Entity;

use Ecommerce\Entity\GeoCountry;
use Ecommerce\Entity\GeoSubdivision;
use Doctrine\ORM\EntityRepository;

class GeoLocationRepository extends EntityRepository
{

	public function getLocations(GeoCountry $country, $subdivision, $return_builder = FALSE)
	{
		//Some countries do not have subdivision
		if(!empty($subdivision) and $subdivision instanceof GeoSubdivision)
		{
			$locations = $this->createQueryBuilder('gl')
				->where('gl.country = :country AND gl.subdivision = :subdivision')
				->setParameters(array('country' => $country, 'subdivision' => $subdivision))
				->orderBy('gl.label', 'ASC');
		}
		else
		{
			$locations = $this->createQueryBuilder('gl')
				->where('gl.country = :country')
				->setParameter('country', $country)
				->orderBy('gl.label', 'ASC');
		}

		return $return_builder ? $locations : $locations->getQuery()->getResult();
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
