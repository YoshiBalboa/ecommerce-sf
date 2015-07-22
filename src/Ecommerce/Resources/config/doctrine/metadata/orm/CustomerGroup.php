<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerGroup
 *
 * @ORM\Table(name="customer_group")
 * @ORM\Entity
 */
class CustomerGroup
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="group_id", type="smallint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $groupId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="group_code", type="string", length=32, nullable=false)
	 */
	private $groupCode = '';
}
