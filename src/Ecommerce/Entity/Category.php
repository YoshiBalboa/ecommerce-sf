<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="fk_category_type", columns={"type_id"}), @ORM\Index(name="fk_category_label", columns={"label_id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\CategoryRepository")
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



    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set label
     *
     * @param \Ecommerce\Entity\AttributeLabel $label
     * @return Category
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
     * Set type
     *
     * @param \Ecommerce\Entity\AttributeType $type
     * @return Category
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
