<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Brand
 *
 * @ORM\Table(name="brand", indexes={@ORM\Index(name="fk_brand_type", columns={"type_id"}), @ORM\Index(name="fk_brand_label", columns={"label_id"}), @ORM\Index(name="fk_brand_category", columns={"category_id"}), @ORM\Index(name="fk_brand_subcategory", columns={"subcategory_id"})})
 * @ORM\Entity
 */
class Brand
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="brand_id", type="smallint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $brandId;

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
	 * @var \Subcategory
	 *
	 * @ORM\ManyToOne(targetEntity="Subcategory")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="subcategory_id")
	 * })
	 */
	private $subcategory;

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
