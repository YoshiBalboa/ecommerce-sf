<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class SubcategoryRepository extends EntityRepository
{

	/**
	 * Return subcategories entities as an array in an array
	 *
	 * @return array
	 */
	public function getViewSubcategories($locale = 'fr')
	{
		$em = $this->getEntityManager();
		$attr_value_repository = $em->getRepository('Ecommerce:AttributeValue');
		$obj_subcategories = $this->findBy(array(), array('createdAt' => 'ASC'));

		$subcategories = array();
		foreach($obj_subcategories as $idx => $subcategory)
		{
			$category_value = $attr_value_repository->findOneBy(array(
				'label'	 => $subcategory->getCategory()->getLabel(),
				'locale' => $locale
			));

			$value_fr = $attr_value_repository->findOneBy(array(
				'label'	 => $subcategory->getLabel(),
				'locale' => 'fr'
			));

			$value_en = $attr_value_repository->findOneBy(array(
				'label'	 => $subcategory->getLabel(),
				'locale' => 'en'
			));

			$subcategories[$idx] = array(
				'subcategory_id' => $subcategory->getSubcategoryId(),
				'category'		 => array(
					'category_id'	 => $subcategory->getCategory()->getCategoryId(),
					'name'			 => $category_value->getName(),
					'urlkey'		 => $category_value->getUrlkey(),
				),
				'type_id'		 => $subcategory->getType()->getTypeId(),
				'label_id'		 => $subcategory->getLabel()->getLabelId(),
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
				'is_active'		 => $subcategory->getIsActive(),
			);
		}

		return $subcategories;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
