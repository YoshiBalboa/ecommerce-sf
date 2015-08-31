<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ecommerce\Entity\CustomerGroupRepository;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter as camel2snake;

/**
 * Customer
 *
 * @ORM\Table(name="customer", uniqueConstraints={@ORM\UniqueConstraint(name="uk_customer_entity_email", columns={"email"})}, indexes={@ORM\Index(name="idx_createdat", columns={"created_at"}), @ORM\Index(name="fk_customer_group", columns={"group_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\CustomerRepository")
 */
class Customer implements AdvancedUserInterface, \Serializable
{

	/**
	 * @var integer
	 * @ORM\Column(name="customer_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $customerId;

	/**
	 * @var string
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
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	private $createdAt = '0000-00-00 00:00:00';

	/**
	 * @var \DateTime
	 * @ORM\Column(name="updated_at", type="datetime", nullable=false)
	 */
	private $updatedAt = '0000-00-00 00:00:00';

	/**
	 * @var boolean
	 * @ORM\Column(name="is_active", type="boolean", nullable=false)
	 */
	private $isActive = '1';

	/**
	 * @var \CustomerGroup
	 * @ORM\ManyToOne(targetEntity="CustomerGroup")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
	 * })
	 */
	private $group;

	//GETTERS AND SETTERS
	/**
	 * Get customerId
	 * @return integer
	 */
	public function getCustomerId()
	{
		return $this->customerId;
	}

	/**
	 * Set email
	 * @param string $email
	 * @return Customer
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * Get email
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return Customer
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * Get password
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set createdAt
	 * @param \DateTime $createdAt
	 * @return Customer
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	/**
	 * Get createdAt
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set updatedAt
	 * @param \DateTime $updatedAt
	 * @return Customer
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
		return $this;
	}

	/**
	 * Get updatedAt
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * Set isActive
	 * @param boolean $isActive
	 * @return Customer
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
		return $this;
	}

	/**
	 * Get isActive
	 * @return boolean
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * Set group
	 * @param CustomerGroup $group
	 * @return Customer
	 */
	public function setGroup(CustomerGroup $group)
	{
		$this->group = $group;
		return $this;
	}

	/**
	 * Get group
	 * @return CustomerGroup
	 */
	public function getGroup()
	{
		return $this->group;
	}

	/**
	 * Get group_id
	 * @return smallint
	 */
	public function getGroupId()
	{
		return $this->group->getGroupId();
	}

	public function getUsername()
	{
		return $this->email;
	}

	public function getRoles()
	{
		$roles = array(
			CustomerGroupRepository::GROUP_DEFAULT => array('ROLE_USER'),
			CustomerGroupRepository::GROUP_UNSUBSCRIBE => array('ROLE_USER'),
			CustomerGroupRepository::GROUP_PRO => array('ROLE_PRO', 'ROLE_USER'),
			CustomerGroupRepository::GROUP_ADMIN => array('ROLE_ADMIN', 'ROLE_PRO', 'ROLE_USER'),
		);

		return $roles[$this->getGroupId()];
	}

	public function getSalt()
	{
		// you *may* need a real salt depending on your encoder
		// All passwords must be hashed with a salt, but bcrypt does this internally
		return null;
	}

	//FUNCTIONS

	public function isAccountNonExpired()
	{
		return true;
	}

	public function isAccountNonLocked()
	{
		return true;
	}

	public function isCredentialsNonExpired()
	{
		return true;
	}

	public function isEnabled()
	{
		return $this->isActive;
	}

	public function eraseCredentials()
	{

	}

	/**
	 * Retrieves all fields in an array
	 * @return array
	 */
	public function toArray()
	{
		$camel2snake = new camel2snake();
		$reflect = new \ReflectionClass($this);
		$props = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);

		$return = array();
		foreach($props as $prop)
		{
			if($prop->getName() == 'password')
			{
				continue;
			}
			elseif($prop->getName() == 'createdAt' or $prop->getName() == 'updatedAt')
			{
				$return[$camel2snake->normalize($prop->getName())] = $this->{$prop->getName()}->format('Y-m-d H:i:s');
			}
			elseif($prop->getName() == 'group')
			{
				$return['group_id'] = $this->getGroupId();
			}
			else
			{
				$return[$camel2snake->normalize($prop->getName())] = $this->{$prop->getName()};
			}
		}

		return $return;
	}

	/** @see \Serializable::serialize() */
	public function serialize()
	{
		return serialize(array(
			$this->customerId,
			$this->email,
			$this->password,
			$this->isActive,
		));
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
			$this->customerId,
			$this->email,
			$this->password,
			$this->isActive,
			) = unserialize($serialized);
	}

}
