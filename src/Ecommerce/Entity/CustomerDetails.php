<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerDetails
 *
 * @ORM\Table(name="customer_details", uniqueConstraints={@ORM\UniqueConstraint(name="uk_customer_details_sponsorship", columns={"sponsorship_key"})}, indexes={@ORM\Index(name="fk_customer_details_billing", columns={"default_billing_id"}), @ORM\Index(name="fk_customer_details_shipping", columns={"default_shipping_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\CustomerDetailsRepository")
 */
class CustomerDetails
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
	 * @ORM\Column(name="crypt_current_vector", type="string", length=255, nullable=true)
	 */
	private $cryptCurrentVector;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="locale", type="string", length=5, nullable=true)
	 */
	private $locale = 'fr_FR';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="sponsorship_key", type="string", length=255, nullable=true)
	 */
	private $sponsorshipKey;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="facebook_user_id", type="string", length=255, nullable=true)
	 */
	private $facebookUserId;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="cgu_validated_at", type="datetime", nullable=true)
	 */
	private $cguValidatedAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="birthday", type="date", nullable=true)
	 */
	private $birthday;

	/**
	 * @var \CustomerAddress
	 *
	 * @ORM\ManyToOne(targetEntity="CustomerAddress")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="default_billing_id", referencedColumnName="address_id")
	 * })
	 */
	private $defaultBilling;

	/**
	 * @var \Customer
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="Customer")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
	 * })
	 */
	private $customer;

	/**
	 * @var \CustomerAddress
	 *
	 * @ORM\ManyToOne(targetEntity="CustomerAddress")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="default_shipping_id", referencedColumnName="address_id")
	 * })
	 */
	private $defaultShipping;

	/**
	 * Set prefix
	 *
	 * @param string $prefix
	 * @return CustomerDetails
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
	 * @return CustomerDetails
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
	 * @return CustomerDetails
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
	 * Set cryptCurrentVector
	 *
	 * @param string $cryptCurrentVector
	 * @return CustomerDetails
	 */
	public function setCryptCurrentVector($cryptCurrentVector)
	{
		$this->cryptCurrentVector = $cryptCurrentVector;

		return $this;
	}

	/**
	 * Get cryptCurrentVector
	 *
	 * @return string
	 */
	public function getCryptCurrentVector()
	{
		return $this->cryptCurrentVector;
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 * @return CustomerDetails
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * Set sponsorshipKey
	 *
	 * @param string $sponsorshipKey
	 * @return CustomerDetails
	 */
	public function setSponsorshipKey($sponsorshipKey)
	{
		$this->sponsorshipKey = $sponsorshipKey;

		return $this;
	}

	/**
	 * Get sponsorshipKey
	 *
	 * @return string
	 */
	public function getSponsorshipKey()
	{
		return $this->sponsorshipKey;
	}

	/**
	 * Set facebookUserId
	 *
	 * @param string $facebookUserId
	 * @return CustomerDetails
	 */
	public function setFacebookUserId($facebookUserId)
	{
		$this->facebookUserId = $facebookUserId;

		return $this;
	}

	/**
	 * Get facebookUserId
	 *
	 * @return string
	 */
	public function getFacebookUserId()
	{
		return $this->facebookUserId;
	}

	/**
	 * Set cguValidatedAt
	 *
	 * @param \DateTime $cguValidatedAt
	 * @return CustomerDetails
	 */
	public function setCguValidatedAt($cguValidatedAt)
	{
		$this->cguValidatedAt = $cguValidatedAt;

		return $this;
	}

	/**
	 * Get cguValidatedAt
	 *
	 * @return \DateTime
	 */
	public function getCguValidatedAt()
	{
		return $this->cguValidatedAt;
	}

	/**
	 * Set birthday
	 *
	 * @param \DateTime $birthday
	 * @return CustomerDetails
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;

		return $this;
	}

	/**
	 * Get birthday
	 *
	 * @return \DateTime
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * Set defaultBilling
	 *
	 * @param \Ecommerce\Entity\CustomerAddress $defaultBilling
	 * @return CustomerDetails
	 */
	public function setDefaultBilling(\Ecommerce\Entity\CustomerAddress $defaultBilling = null)
	{
		$this->defaultBilling = $defaultBilling;

		return $this;
	}

	/**
	 * Get defaultBilling
	 *
	 * @return \Ecommerce\Entity\CustomerAddress
	 */
	public function getDefaultBilling()
	{
		return $this->defaultBilling;
	}

	/**
	 * Set customer
	 *
	 * @param \Ecommerce\Entity\Customer $customer
	 * @return CustomerDetails
	 */
	public function setCustomer(\Ecommerce\Entity\Customer $customer)
	{
		$this->customer = $customer;

		return $this;
	}

	/**
	 * Get customer
	 *
	 * @return \Ecommerce\Entity\Customer
	 */
	public function getCustomer()
	{
		return $this->customer;
	}

	/**
	 * Set defaultShipping
	 *
	 * @param \Ecommerce\Entity\CustomerAddress $defaultShipping
	 * @return CustomerDetails
	 */
	public function setDefaultShipping(\Ecommerce\Entity\CustomerAddress $defaultShipping = null)
	{
		$this->defaultShipping = $defaultShipping;

		return $this;
	}

	/**
	 * Get defaultShipping
	 *
	 * @return \Ecommerce\Entity\CustomerAddress
	 */
	public function getDefaultShipping()
	{
		return $this->defaultShipping;
	}

}
