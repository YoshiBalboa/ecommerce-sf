<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttributeLabel
 *
 * @ORM\Table(name="attribute_label", indexes={@ORM\Index(name="fk_label_type", columns={"type_id"})})
 * @ORM\Entity
 */
class AttributeLabel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="label_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $labelId;

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
     * Get labelId
     *
     * @return integer
     */
    public function getLabelId()
    {
        return $this->labelId;
    }

    /**
     * Set type
     *
     * @param \Ecommerce\Entity\AttributeType $type
     * @return AttributeLabel
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
