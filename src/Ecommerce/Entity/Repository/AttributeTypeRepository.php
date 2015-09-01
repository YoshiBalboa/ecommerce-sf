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

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
