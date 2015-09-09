<?php

namespace Ecommerce\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ecommerce\Entity\Category;

class CategoryToNumberTransformer implements DataTransformerInterface
{

	/**
	 * @var ObjectManager
	 */
	private $om;

	/**
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}

	/**
	 * Transforms an object (category) to a integer (id).
	 *
	 * @param  Category|null $category
	 * @return integer
	 */
	public function transform($category)
	{
		if(null === $category)
		{
			return "";
		}

		return $category->getCategoryId();
	}

	/**
	 * Transforms a integer (id) to an object (category).
	 *
	 * @param  integer $id
	 * @return Category|null
	 * @throws TransformationFailedException if object (category) is not found.
	 */
	public function reverseTransform($id)
	{
		if(!$id)
		{
			return null;
		}

		$category = $this->om
			->getRepository('Ecommerce:Category')
			->findOneBy(array('categoryId' => $id))
		;

		if(null === $category)
		{
			throw new TransformationFailedException(sprintf(
				'The category %s can not be found!', $id
			));
		}

		return $category;
	}

}
