<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerAddress
 *
 * @ORM\Table(name="customer_address", indexes={@ORM\Index(name="fk_customer_address_customer", columns={"customer_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\CustomerAddressRepository")
 */
class CustomerAddress
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="address_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $addressId;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	private $createdAt = '0000-00-00 00:00:00';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="updated_at", type="datetime", nullable=false)
	 */
	private $updatedAt = '0000-00-00 00:00:00';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean", nullable=false)
	 */
	private $isActive = '1';

	/**
	 * @var \Customer
	 *
	 * @ORM\ManyToOne(targetEntity="Customer")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
	 * })
	 */
	private $customer;

	/**
	 * Get addressId
	 *
	 * @return integer
	 */
	public function getAddressId()
	{
		return $this->addressId;
	}

	/**
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 * @return CustomerAddress
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get createdAt
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set updatedAt
	 *
	 * @param \DateTime $updatedAt
	 * @return CustomerAddress
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * Get updatedAt
	 *
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * Set isActive
	 *
	 * @param boolean $isActive
	 * @return CustomerAddress
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get isActive
	 *
	 * @return boolean
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * Set customer
	 *
	 * @param \Ecommerce\Entity\Customer $customer
	 * @return CustomerAddress
	 */
	public function setCustomer(\Ecommerce\Entity\Customer $customer = null)
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

}
