<?php

namespace Ecommerce\Entity\Repository;

use Ecommerce\Entity\Attribute;
use Ecommerce\Entity\Category;
use Ecommerce\Entity\LinkedAttribute;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class LinkedAttributeRepository extends EntityRepository
{

	const batchSize = 20;

	public function getLinks(Attribute $attribute)
	{
		$dql = 'SELECT IDENTITY(l.attribute) AS attribute_id, IDENTITY(l.category) AS category_id, IDENTITY(l.subcategory) AS subcategory_id
				FROM Ecommerce:LinkedAttribute l
				WHERE l.attribute = :attribute
				GROUP BY l.category, l.subcategory
				ORDER BY l.category ASC, l.subcategory ASC';

		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('attribute', $attribute);

		return $query->getResult(Query::HYDRATE_ARRAY);
	}

	public function getCategories(Attribute $attribute)
	{
		$dql = 'SELECT IDENTITY(l.category) AS category_id
				FROM Ecommerce:LinkedAttribute l
				WHERE l.attribute = :attribute
				AND l.subcategory IS NULL
				GROUP BY l.category
				ORDER BY l.category ASC';

		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('attribute', $attribute);

		$categories = array();
		foreach($query->getResult(Query::HYDRATE_ARRAY) as $row)
		{
			$categories[] = $row['category_id'];
		}

		return $categories;
	}

	public function getSubcategories(Attribute $attribute)
	{
		$dql = 'SELECT IDENTITY(l.subcategory) AS subcategory_id
				FROM Ecommerce:LinkedAttribute l
				WHERE l.attribute = :attribute
				AND l.subcategory IS NOT NULL
				GROUP BY l.subcategory
				ORDER BY l.subcategory ASC';

		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('attribute', $attribute);

		$subcategories = array();
		foreach($query->getResult(Query::HYDRATE_ARRAY) as $row)
		{
			$subcategories[] = $row['subcategory_id'];
		}

		return $subcategories;
	}

	public function createLinks(Attribute $attribute, Array $rows)
	{
		$em = $this->getEntityManager();

		$attribute_repository = $em->getRepository('Ecommerce:Attribute');
		$category_repository = $em->getRepository('Ecommerce:Category');
		$subcategory_repository = $em->getRepository('Ecommerce:Subcategory');

		foreach($rows as $i => $row)
		{
			if(!array_key_exists('category', $row) or ! array_key_exists('subcategory', $row))
			{
				continue;
			}

			$persistent_attr = $attribute_repository->findOneByAttributeId($attribute->getAttributeId());
			$persistent_cat = $category_repository->findOneByCategoryId($row['category']->getCategoryId());
			$persistent_subcat = empty($row['subcategory']) ? NULL : $subcategory_repository->findOneBySubcategoryId($row['subcategory']->getSubcategoryId());

			$link = new LinkedAttribute();
			$link->setAttribute($persistent_attr);
			$link->setCategory($persistent_cat);
			$link->setSubcategory($persistent_subcat);

			$em->persist($link);

			// flush everything to the database every 'batchSize' inserts
			if(($i % self::batchSize) == 0)
			{
				$em->flush();
				$em->clear();
			}
		}

		// flush the remaining objects
		$em->flush();
		$em->clear();
	}

	public function deleteLinks(Attribute $attribute)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();

		$query = $qb->delete('Ecommerce:LinkedAttribute', 'a')
			->where('a.attribute = :attribute')
			->setParameter('attribute', $attribute)
			->getQuery();
		$query->execute();
	}

	/**
	 * When a new category is create, generate links with existing attributes
	 * @param Category $category
	 */
	public function generateAttributeLinks(Category $category)
	{
		$em = $this->getEntityManager();

		$attribute_repository = $em->getRepository('Ecommerce:Attribute');
		$category_repository = $em->getRepository('Ecommerce:Category');
		$attributes = $attribute_repository->findAll();

		foreach($attributes as $i => $attribute)
		{
			$persistent_attr = $attribute_repository->findOneByAttributeId($attribute->getAttributeId());
			$persistent_cat = $category_repository->findOneByCategoryId($category->getCategoryId());

			$link = new LinkedAttribute();
			$link->setAttribute($persistent_attr);
			$link->setCategory($persistent_cat);
			$link->setSubcategory(NULL);

			$em->persist($link);

			// flush everything to the database every 'batchSize' inserts
			if(($i % self::batchSize) == 0)
			{
				$em->flush();
				$em->clear();
			}
		}

		// flush the remaining objects
		$em->flush();
		$em->clear();
	}

	/**
	 * When a new attribute is create, generate links with existing categories
	 * @param Attribute $attribute
	 */
	public function generateCategoryLinks(Attribute $attribute)
	{
		$em = $this->getEntityManager();

		$attribute_repository = $em->getRepository('Ecommerce:Attribute');
		$category_repository = $em->getRepository('Ecommerce:Category');
		$categories = $category_repository->findAll();

		foreach($categories as $i => $category)
		{
			$persistent_attr = $attribute_repository->findOneByAttributeId($attribute->getAttributeId());
			$persistent_cat = $category_repository->findOneByCategoryId($category->getCategoryId());

			$link = new LinkedAttribute();
			$link->setAttribute($persistent_attr);
			$link->setCategory($persistent_cat);
			$link->setSubcategory(NULL);

			$em->persist($link);

			// flush everything to the database every 'batchSize' inserts
			if(($i % self::batchSize) == 0)
			{
				$em->flush();
				$em->clear();
			}
		}

		$em->flush();
		$em->clear();
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
