<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\AttributeType;

class AttributeRepository extends EntityRepository
{

	/**
	 * Return attribute entities as an array in an array
	 * This array is for rendering attributes in view
	 *
	 * @return array
	 */
	public function getViewAttributes(AttributeType $type)
	{
		$em = $this->getEntityManager();
		$attr_value_repository = $em->getRepository('Ecommerce:AttributeValue');
		$obj_attributes = $this->findBy(array('type' => $type), array('createdAt' => 'ASC'));

		$attributes = array();
		foreach($obj_attributes as $idx => $attribute)
		{
			$value_fr = $attr_value_repository->findOneBy(array(
				'label'	 => $attribute->getLabel(),
				'locale' => 'fr'
			));

			$value_en = $attr_value_repository->findOneBy(array(
				'label'	 => $attribute->getLabel(),
				'locale' => 'en'
			));

			$attributes[$idx] = array(
				'attribute_id'	 => $attribute->getAttributeId(),
				'type_id'		 => $attribute->getType()->getTypeId(),
				'label_id'		 => $attribute->getLabel()->getLabelId(),
				'values'		 => array(
					'fr' => array(
						'value_id'	 => $value_fr->getValueId(),
						'name'		 => $value_fr->getName(),
						'urlkey'	 => $value_fr->getUrlkey()
					),
					'en' => array(
						'value_id'	 => $value_en->getValueId(),
						'name'		 => $value_en->getName(),
						'urlkey'	 => $value_en->getUrlkey()
					),
				),
				'is_active'		 => $attribute->getIsActive(),
			);
		}

		return $attributes;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
