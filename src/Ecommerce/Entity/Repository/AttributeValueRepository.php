<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class AttributeValueRepository extends EntityRepository
{

	public function existsLabel($type, $name, $locale)
	{
		$em = $this->getEntityManager();

		$sql = '
			SELECT v.label_id
			FROM attribute_value AS v
			INNER JOIN attribute_label AS l ON v.label_id = l.label_id AND l.type_id = :type
			WHERE v.hash = SHA1(:name) AND v.locale = :locale
		';

		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Ecommerce:AttributeValue', 'v');
		$rsm->addFieldResult('v', 'value_id', 'value_id');

		$query = $em->createNativeQuery($sql, $rsm);
		$query->setParameters(array('type' => $type, 'name' => $name, 'locale' => $locale));

		$label = $query->getOneOrNullResult();

		return !empty($label);
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
