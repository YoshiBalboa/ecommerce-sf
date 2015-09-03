<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Ecommerce\Entity\Category;

class CategoryRepository extends EntityRepository
{

	/**
	 * Return categories entities as an array in an array
	 *
	 * @return array
	 */
	public function getCategories()
	{
		$em = $this->getEntityManager();
		$attr_value_repository = $em->getRepository('Ecommerce:AttributeValue');
		$obj_categories = $this->findBy(array(), array('createdAt' => 'ASC'));

		$categories = array();
		foreach($obj_categories as $idx => $category)
		{
			$value_fr = $attr_value_repository->findOneBy(array(
				'label'	 => $category->getLabel(),
				'locale' => 'fr'
			));

			$value_en = $attr_value_repository->findOneBy(array(
				'label'	 => $category->getLabel(),
				'locale' => 'en'
			));

			$categories[$idx] = array(
				'category_id'	 => $category->getCategoryId(),
				'type_id'		 => $category->getType()->getTypeId(),
				'label_id'		 => $category->getLabel()->getLabelId(),
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
				'is_active'		 => $category->getIsActive(),
			);
		}

		return $categories;
	}

	/**
	 * Disabled all subcategories if the parent category is inactive
	 * @param Category $category
	 * @return type
	 */
	public function disableSubcategories(Category $category)
	{
		if($category->getIsActive())
		{
			return FALSE;
		}

		$qb = $this->getEntityManager()->createQueryBuilder();
		$query = $qb->update('Ecommerce:Subcategory', 's')
			->set('s.isActive', $qb->expr()->literal(0))
			->where('s.category = :category')
			->setParameter('category', $category)
			->getQuery();
		$query->execute();

		return TRUE;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
