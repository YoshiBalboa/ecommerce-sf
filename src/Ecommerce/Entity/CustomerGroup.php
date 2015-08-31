<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerGroup
 *
 * @ORM\Table(name="customer_group")
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\CustomerGroupRepository")
 */
class CustomerGroup
{

	/**
	 * @var integer
	 * @ORM\Column(name="group_id", type="smallint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $groupId;

	/**
	 * @var string
	 * @ORM\Column(name="group_code", type="string", length=32, nullable=false)
	 */
	private $groupCode = '';

	/**
	 * Get groupId
	 * @return integer
	 */
	public function getGroupId()
	{
		return $this->groupId;
	}

	/**
	 * Set groupCode
	 * @param string $groupCode
	 * @return CustomerGroup
	 */
	public function setGroupCode($groupCode)
	{
		$this->groupCode = $groupCode;

		return $this;
	}

	/**
	 * Get groupCode
	 *
	 * @return string
	 */
	public function getGroupCode()
	{
		return $this->groupCode;
	}

}
