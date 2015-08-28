<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Material
 *
 * @ORM\Table(name="material", indexes={@ORM\Index(name="fk_material_type", columns={"type_id"}), @ORM\Index(name="fk_material_label", columns={"label_id"}), @ORM\Index(name="fk_material_category", columns={"category_id"}), @ORM\Index(name="fk_material_subcategory", columns={"subcategory_id"})})
 * @ORM\Entity
 */
class Material
{
    /**
     * @var integer
     *
     * @ORM\Column(name="material_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $materialId;

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



    /**
     * Get materialId
     *
     * @return integer 
     */
    public function getMaterialId()
    {
        return $this->materialId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Material
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
     * @return Material
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
     * @return Material
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
     * Set category
     *
     * @param \Ecommerce\Entity\Category $category
     * @return Material
     */
    public function setCategory(\Ecommerce\Entity\Category $category = null)
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
     * Set label
     *
     * @param \Ecommerce\Entity\AttributeLabel $label
     * @return Material
     */
    public function setLabel(\Ecommerce\Entity\AttributeLabel $label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return \Ecommerce\Entity\AttributeLabel 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set subcategory
     *
     * @param \Ecommerce\Entity\Subcategory $subcategory
     * @return Material
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

    /**
     * Set type
     *
     * @param \Ecommerce\Entity\AttributeType $type
     * @return Material
     */
    public function setType(\Ecommerce\Entity\AttributeType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Ecommerce\Entity\AttributeType 
     */
    public function getType()
    {
        return $this->type;
    }
}
