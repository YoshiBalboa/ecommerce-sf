<?php

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
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=40, nullable=false)
     */
    private $hash = '';

	/**
	 * @var \AttributeLabel
	 *
	 * @ORM\ManyToOne(targetEntity="AttributeLabel")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="label_id", referencedColumnName="label_id")
	 * })
	 */
	private $label;

}
