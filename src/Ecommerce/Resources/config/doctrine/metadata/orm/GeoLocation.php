<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoLocation
 *
 * @ORM\Table(name="geo_location", uniqueConstraints={@ORM\UniqueConstraint(name="uk_row", columns={"code", "subdivision_id"})}, indexes={@ORM\Index(name="fk_geo_location_subdivision", columns={"subdivision_id"}), @ORM\Index(name="fk_geo_location_country", columns={"country_id"})})
 * @ORM\Entity
 */
class GeoLocation
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
	 * @var string
	 *
	 * @ORM\Column(name="latitude", type="decimal", precision=18, scale=12, nullable=true)
	 */
	private $latitude;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longitude", type="decimal", precision=18, scale=12, nullable=true)
	 */
	private $longitude;

	/**
	 * @var \GeoCountry
	 *
	 * @ORM\ManyToOne(targetEntity="GeoCountry")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
	 * })
	 */
	private $country;

	/**
	 * @var \GeoSubdivision
	 *
	 * @ORM\ManyToOne(targetEntity="GeoSubdivision")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="subdivision_id", referencedColumnName="id")
	 * })
	 */
	private $subdivision;

}
