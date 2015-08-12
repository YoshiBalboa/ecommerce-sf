<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerAddressDetails
 *
 * @ORM\Table(name="customer_address_details", indexes={@ORM\Index(name="fk_customer_address_details_country", columns={"country_id"}), @ORM\Index(name="fk_customer_address_details_subdivision", columns={"subdivision_id"}), @ORM\Index(name="fk_customer_address_details_location", columns={"location_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\CustomerAddressDetailsRepository")
 */
class CustomerAddressDetails
{

	/**
	 * @var string
	 *
	 * @ORM\Column(name="prefix", type="string", length=5, nullable=true)
	 */
	private $prefix;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
	 */
	private $firstname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
	 */
	private $lastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="street", type="text", nullable=true)
	 */
	private $street;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="postcode", type="string", length=255, nullable=true)
	 */
	private $postcode;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
	 */
	private $telephone;

	/**
	 * @var \CustomerAddress
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="CustomerAddress")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="address_id", referencedColumnName="address_id")
	 * })
	 */
	private $address;

	/**
	 * @var \GeoLocation
	 *
	 * @ORM\ManyToOne(targetEntity="GeoLocation")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="location_id", referencedColumnName="id")
	 * })
	 */
	private $location;

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
	 * @var \GeoCountry
	 *
	 * @ORM\ManyToOne(targetEntity="GeoCountry")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
	 * })
	 */
	private $country;

	/**
	 * Set prefix
	 *
	 * @param string $prefix
	 * @return CustomerAddressDetails
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;

		return $this;
	}

	/**
	 * Get prefix
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * Set firstname
	 *
	 * @param string $firstname
	 * @return CustomerAddressDetails
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 * @return CustomerAddressDetails
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Set street
	 *
	 * @param string $street
	 * @return CustomerAddressDetails
	 */
	public function setStreet($street)
	{
		$this->street = $street;

		return $this;
	}

	/**
	 * Get street
	 *
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * Set postcode
	 *
	 * @param string $postcode
	 * @return CustomerAddressDetails
	 */
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;

		return $this;
	}

	/**
	 * Get postcode
	 *
	 * @return string
	 */
	public function getPostcode()
	{
		return $this->postcode;
	}

	/**
	 * Set telephone
	 *
	 * @param string $telephone
	 * @return CustomerAddressDetails
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;

		return $this;
	}

	/**
	 * Get telephone
	 *
	 * @return string
	 */
	public function getTelephone()
	{
		return $this->telephone;
	}

	/**
	 * Set address
	 *
	 * @param \Ecommerce\Entity\CustomerAddress $address
	 * @return CustomerAddressDetails
	 */
	public function setAddress(\Ecommerce\Entity\CustomerAddress $address)
	{
		$this->address = $address;

		return $this;
	}

	/**
	 * Get address
	 *
	 * @return \Ecommerce\Entity\CustomerAddress
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Set location
	 *
	 * @param \Ecommerce\Entity\GeoLocation $location
	 * @return CustomerAddressDetails
	 */
	public function setLocation(\Ecommerce\Entity\GeoLocation $location = null)
	{
		$this->location = $location;

		return $this;
	}

	/**
	 * alias for setLocation
	 */
	public function setCity(\Ecommerce\Entity\GeoLocation $location = null)
	{
		return $this->setLocation($location);
	}

	/**
	 * Get location
	 *
	 * @return \Ecommerce\Entity\GeoLocation
	 */
	public function getLocation()
	{
		return $this->location;
	}

	/**
	 * alias for getLocation
	 */
	public function getCity()
	{
		return $this->getLocation();
	}

	/**
	 * Set subdivision
	 *
	 * @param \Ecommerce\Entity\GeoSubdivision $subdivision
	 * @return CustomerAddressDetails
	 */
	public function setSubdivision(\Ecommerce\Entity\GeoSubdivision $subdivision = null)
	{
		$this->subdivision = $subdivision;

		return $this;
	}

	/**
	 * alias for setSubdivision
	 */
	public function setState(\Ecommerce\Entity\GeoSubdivision $subdivision = null)
	{
		return $this->setSubdivision($subdivision);
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

	/**
	 * alias for getSubdivision
	 */
	public function getState()
	{
		return $this->getSubdivision();
	}

	/**
	 * Set country
	 *
	 * @param \Ecommerce\Entity\GeoCountry $country
	 * @return CustomerAddressDetails
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
