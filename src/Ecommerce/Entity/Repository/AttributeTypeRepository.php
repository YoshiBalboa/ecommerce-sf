<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AttributeTypeRepository extends EntityRepository
{

	const TYPE_CATEGORY = 1;
	const TYPE_SUBCATEGORY = 2;
	const TYPE_BRAND = 3;
	const TYPE_COLOR = 4;
	const TYPE_MATERIAL = 5;

	public function getTypes($return_builder = FALSE)
	{
		$qr = $this->getEntityManager()->createQueryBuilder();
		$types = $this->createQueryBuilder('t')
			->where($qr->expr()->notIn('t.typeId', array(self::TYPE_CATEGORY, self::TYPE_SUBCATEGORY)))
			->orderBy('t.code', 'ASC');

		if(!$return_builder)
		{
			return $types->getQuery()->execute();
		}

		return $types;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
