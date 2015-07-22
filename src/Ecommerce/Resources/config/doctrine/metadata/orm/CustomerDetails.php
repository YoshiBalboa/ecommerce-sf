<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerDetails
 *
 * @ORM\Table(name="customer_details", uniqueConstraints={@ORM\UniqueConstraint(name="uk_customer_details_sponsorship", columns={"sponsorship_key"})}, indexes={@ORM\Index(name="fk_customer_details_billing", columns={"default_billing_id"}), @ORM\Index(name="fk_customer_details_shipping", columns={"default_shipping_id"})})
 * @ORM\Entity
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
	 * @ORM\Column(name="password", type="string", length=255, nullable=true)
	 */
	private $password;

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
	 *	 @ORM\JoinColumn(name="default_billing_id", referencedColumnName="address_id")
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
	 *	 @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
	 * })
	 */
	private $customer;

	/**
	 * @var \CustomerAddress
	 *
	 * @ORM\ManyToOne(targetEntity="CustomerAddress")
	 * @ORM\JoinColumns({
	 *	 @ORM\JoinColumn(name="default_shipping_id", referencedColumnName="address_id")
	 * })
	 */
	private $defaultShipping;
}
