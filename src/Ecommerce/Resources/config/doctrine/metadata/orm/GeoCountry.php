<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoCountry
 *
 * @ORM\Table(name="geo_country", uniqueConstraints={@ORM\UniqueConstraint(name="uk_row", columns={"code"})})
 * @ORM\Entity
 */
class GeoCountry
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="code", type="string", length=5, nullable=false)
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", length=255, nullable=false)
	 */
	private $label;
}
