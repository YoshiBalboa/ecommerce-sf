<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoSubdivision
 *
 * @ORM\Table(name="geo_subdivision", uniqueConstraints={@ORM\UniqueConstraint(name="uk_row", columns={"code", "country_id"})}, indexes={@ORM\Index(name="fk_geo_subdivision_country", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\GeoSubdivisionRepository")
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
	 * @return GeoSubdivision
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
	 * @return GeoSubdivision
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
	 * Set country
	 *
	 * @param \Ecommerce\Entity\GeoCountry $country
	 * @return GeoSubdivision
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

}
