<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoLocation
 *
 * @ORM\Table(name="geo_location", uniqueConstraints={@ORM\UniqueConstraint(name="uk_row", columns={"code", "subdivision_id"})}, indexes={@ORM\Index(name="fk_geo_location_subdivision", columns={"subdivision_id"}), @ORM\Index(name="fk_geo_location_country", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\GeoLocationRepository")
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

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set code
	 *
	 * @param string $code
	 * @return GeoLocation
	 */
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * Get code
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Set label
	 *
	 * @param string $label
	 * @return GeoLocation
	 */
	public function setLabel($label)
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Set latitude
	 *
	 * @param string $latitude
	 * @return GeoLocation
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;

		return $this;
	}

	/**
	 * Get latitude
	 *
	 * @return string
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * Set longitude
	 *
	 * @param string $longitude
	 * @return GeoLocation
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}

	/**
	 * Get longitude
	 *
	 * @return string
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	/**
	 * Set country
	 *
	 * @param \Ecommerce\Entity\GeoCountry $country
	 * @return GeoLocation
	 */
	public function setCountry(\Ecommerce\Entity\GeoCountry $country = null)
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * Get country
	 *
	 * @return \Ecommerce\Entity\GeoCountry
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Set subdivision
	 *
	 * @param \Ecommerce\Entity\GeoSubdivision $subdivision
	 * @return GeoLocation
	 */
	public function setSubdivision(\Ecommerce\Entity\GeoSubdivision $subdivision = null)
	{
		$this->subdivision = $subdivision;

		return $this;
	}

	/**
	 * Get subdivision
	 *
	 * @return \Ecommerce\Entity\GeoSubdivision
	 */
	public function getSubdivision()
	{
		return $this->subdivision;
	}

}
