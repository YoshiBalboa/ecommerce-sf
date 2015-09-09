<?php

namespace Ecommerce\Entity;

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

	/**
	 * Set attribute
	 *
	 * @param \Ecommerce\Entity\Attribute $attribute
	 * @return LinkedAttribute
	 */
	public function setAttribute(\Ecommerce\Entity\Attribute $attribute)
	{
		$this->attribute = $attribute;

		return $this;
	}

	/**
	 * Get attribute
	 *
	 * @return \Ecommerce\Entity\Attribute
	 */
	public function getAttribute()
	{
		return $this->attribute;
	}

	/**
	 * Set category
	 *
	 * @param \Ecommerce\Entity\Category $category
	 * @return LinkedAttribute
	 */
	public function setCategory(\Ecommerce\Entity\Category $category)
	{
		$this->category = $category;

		return $this;
	}

	/**
	 * Get category
	 *
	 * @return \Ecommerce\Entity\Category
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * Set subcategory
	 *
	 * @param \Ecommerce\Entity\Subcategory $subcategory
	 * @return LinkedAttribute
	 */
	public function setSubcategory(\Ecommerce\Entity\Subcategory $subcategory = null)
	{
		$this->subcategory = $subcategory;

		return $this;
	}

	/**
	 * Get subcategory
	 *
	 * @return \Ecommerce\Entity\Subcategory
	 */
	public function getSubcategory()
	{
		return $this->subcategory;
	}

}
