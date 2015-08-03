<?php

namespace Ecommerce\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ecommerce\Entity\GeoLocation;

class GeoLocationToNumberTransformer implements DataTransformerInterface
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
	 * Transforms an object (location) to a integer (id).
	 *
	 * @param  GeoLocation|null $location
	 * @return integer
	 */
	public function transform($location)
	{
		if(null === $location)
		{
			return "";
		}

		return $location->getId();
	}

	/**
	 * Transforms a integer (id) to an object (location).
	 *
	 * @param  integer $id
	 * @return GeoLocation|null
	 * @throws TransformationFailedException if object (location) is not found.
	 */
	public function reverseTransform($id)
	{
		if(!$id)
		{
			return null;
		}

		$location = $this->om
			->getRepository('Ecommerce:GeoLocation')
			->findOneBy(array(
			'id' => $id))
		;

		if(null === $location)
		{
			throw new TransformationFailedException(sprintf(
				'The location %s can not be found!', $id
			));
		}

		return $location;
	}
}