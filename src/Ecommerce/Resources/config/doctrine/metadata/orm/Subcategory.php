<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Subcategory
 *
 * @ORM\Table(name="subcategory", indexes={@ORM\Index(name="fk_subcategory_type", columns={"type_id"}), @ORM\Index(name="fk_subcategory_label", columns={"label_id"}), @ORM\Index(name="fk_subcategory_category", columns={"category_id"})})
 * @ORM\Entity
 */
class Subcategory
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="subcategory_id", type="smallint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $subcategoryId;

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
	 * @var \Category
	 *
	 * @ORM\ManyToOne(targetEntity="Category")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
	 * })
	 */
	private $category;

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
