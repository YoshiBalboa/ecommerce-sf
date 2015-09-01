<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Ecommerce\Entity\AttributeValue;

class AttributeValueRepository extends EntityRepository
{

	public function existsLabel($type, $name, $locale, AttributeValue $value = null)
	{
		$em = $this->getEntityManager();

		$sql = '
			SELECT v.value_id
			FROM attribute_value AS v
			INNER JOIN attribute_label AS l ON v.label_id = l.label_id AND l.type_id = :type
			WHERE v.hash = SHA1(:name) AND v.locale = :locale
		';

		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Ecommerce:AttributeValue', 'v');
		$rsm->addFieldResult('v', 'value_id', 'valueId');

		$query = $em->createNativeQuery($sql, $rsm);
		$query->setParameters(array('type' => $type, 'name' => $name, 'locale' => $locale));

		$result = $query->getOneOrNullResult();

		if(empty($result) or (!empty($value) and $value->getValueId() == $result->getValueId()))
		{
			//This label isn't use yet
			return FALSE;
		}
		else
		{
			//This label is already use by another attribute
			return TRUE;
		}
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
