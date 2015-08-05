<?php

namespace Ecommerce\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ecommerce\Entity\GeoSubdivision;

class GeoSubdivisionToNumberTransformer implements DataTransformerInterface
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
	 * Transforms an object (subdivision) to a integer (id).
	 *
	 * @param  GeoSubdivision|null $subdivision
	 * @return integer
	 */
	public function transform($subdivision)
	{
		if(null === $subdivision)
		{
			return "";
		}

		return $subdivision->getId();
	}

	/**
	 * Transforms a integer (id) to an object (subdivision).
	 *
	 * @param  integer $id
	 * @return GeoSubdivision|null
	 * @throws TransformationFailedException if object (subdivision) is not found.
	 */
	public function reverseTransform($id)
	{
		if(!$id or $id == '-1')
		{
			return null;
		}

		$subdivision = $this->om
			->getRepository('Ecommerce:GeoSubdivision')
			->findOneBy(array(
			'id' => $id))
		;

		if(null === $subdivision)
		{
			throw new TransformationFailedException(sprintf(
				'The subdivision %s can not be found!', $id
			));
		}

		return $subdivision;
	}

}
