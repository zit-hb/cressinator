<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasurementRepository")
 * @ORM\Table(name="measurements")
 */
class MeasurementEntity implements EntityInterface
{
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    protected $value;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'c'>")
     */
    protected $createdAt;

    /**
     * @var MeasurementSourceEntity|null
     * @ORM\ManyToOne(targetEntity="MeasurementSourceEntity", inversedBy="measurements")
     * @Assert\NotNull()
     * @Serializer\Exclude()
     */
    protected $source;

    /**
     * MeasurementEntity constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return MeasurementSourceEntity|null
     */
    public function getSource(): ?MeasurementSourceEntity
    {
        return $this->source;
    }

    /**
     * @param MeasurementSourceEntity $source
     */
    public function setSource(MeasurementSourceEntity $source)
    {
        $this->source = $source;
    }
}
