<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttributeType
 *
 * @ORM\Table(name="attribute_type", uniqueConstraints={@ORM\UniqueConstraint(name="uk_attribute_type_code", columns={"code"})})
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\AttributeTypeRepository")
 */
class AttributeType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $typeId;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32, nullable=false)
     */
    private $code = '';



    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return AttributeType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
