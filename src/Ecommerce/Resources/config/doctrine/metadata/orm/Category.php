<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="fk_category_type", columns={"type_id"}), @ORM\Index(name="fk_category_label", columns={"label_id"})})
 * @ORM\Entity
 */
class Category
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="category_id", type="smallint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $categoryId;

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
	 * @var \AttributeLabel
	 *
	 * @ORM\ManyToOne(targetEntity="AttributeLabel")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="label_id", referencedColumnName="label_id")
	 * })
	 */
	private $label;

	/**
	 * @var \AttributeType
	 *
	 * @ORM\ManyToOne(targetEntity="AttributeType")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="type_id", referencedColumnName="type_id")
	 * })
	 */
	private $type;

}
