<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer", uniqueConstraints={@ORM\UniqueConstraint(name="uk_customer_entity_email", columns={"email"})}, indexes={@ORM\Index(name="idx_createdat", columns={"created_at"}), @ORM\Index(name="fk_customer_group", columns={"group_id"})})
 * @ORM\Entity
 */
class Customer
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="customer_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $customerId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable=false)
	 */
	private $email = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=255, nullable=false)
	 */
	private $password = '';

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
	 * @var \CustomerGroup
	 *
	 * @ORM\ManyToOne(targetEntity="CustomerGroup")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
	 * })
	 */
	private $group;
}
