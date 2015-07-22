<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoSubdivision
 *
 * @ORM\Table(name="geo_subdivision", uniqueConstraints={@ORM\UniqueConstraint(name="uk_row", columns={"code", "country_id"})}, indexes={@ORM\Index(name="fk_geo_subdivision_country", columns={"country_id"})})
 * @ORM\Entity
 */
class GeoSubdivision
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

	/**
	 * @var \GeoCountry
	 *
	 * @ORM\ManyToOne(targetEntity="GeoCountry")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
	 * })
	 */
	private $country;
}
