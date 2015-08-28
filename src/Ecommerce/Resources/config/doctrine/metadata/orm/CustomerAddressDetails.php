<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerAddressDetails
 *
 * @ORM\Table(name="customer_address_details", indexes={@ORM\Index(name="fk_customer_address_details_country", columns={"country_id"}), @ORM\Index(name="fk_customer_address_details_subdivision", columns={"subdivision_id"}), @ORM\Index(name="fk_customer_address_details_location", columns={"location_id"})})
 * @ORM\Entity
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
	 * @var \GeoCountry
	 *
	 * @ORM\ManyToOne(targetEntity="GeoCountry")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
	 * })
	 */
	private $country;

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

}
