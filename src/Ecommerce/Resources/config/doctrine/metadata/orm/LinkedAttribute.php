<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * LinkedAttribute
 *
 * @ORM\Table(name="linked_attribute", uniqueConstraints={@ORM\UniqueConstraint(name="attribute_id", columns={"attribute_id", "category_id", "subcategory_id"})}, indexes={@ORM\Index(name="fk_linked_attribute_category", columns={"category_id"}), @ORM\Index(name="fk_linked_material_subcategory", columns={"subcategory_id"}), @ORM\Index(name="IDX_EC7820F0B6E62EFA", columns={"attribute_id"})})
 * @ORM\Entity
 */
class LinkedAttribute
{

	/**
	 * @var \Attribute
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="Attribute")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="attribute_id", referencedColumnName="attribute_id")
	 * })
	 */
	private $attribute;

	/**
	 * @var \Category
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="Category")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
	 * })
	 */
	private $category;

	/**
	 * @var \Subcategory
	 *
	 * @ORM\ManyToOne(targetEntity="Subcategory")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="subcategory_id")
	 * })
	 */
	private $subcategory;

}
