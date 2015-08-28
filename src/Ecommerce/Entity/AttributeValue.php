<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttributeValue
 *
 * @ORM\Table(name="attribute_value", uniqueConstraints={@ORM\UniqueConstraint(name="uk_attribute_value_label_locale", columns={"label_id", "locale"})}, indexes={@ORM\Index(name="IDX_FE4FBB8233B92F39", columns={"label_id"})})
 * @ORM\Entity
 */
class AttributeValue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="value_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $valueId;

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
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="urlkey", type="string", length=255, nullable=false)
     */
    private $urlkey = '';

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
     * Get valueId
     *
     * @return integer 
     */
    public function getValueId()
    {
        return $this->valueId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AttributeValue
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
     * @return AttributeValue
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
     * Set locale
     *
     * @param string $locale
     * @return AttributeValue
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AttributeValue
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set urlkey
     *
     * @param string $urlkey
     * @return AttributeValue
     */
    public function setUrlkey($urlkey)
    {
        $this->urlkey = $urlkey;

        return $this;
    }

    /**
     * Get urlkey
     *
     * @return string 
     */
    public function getUrlkey()
    {
        return $this->urlkey;
    }

    /**
     * Set label
     *
     * @param \Ecommerce\Entity\AttributeLabel $label
     * @return AttributeValue
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
}
