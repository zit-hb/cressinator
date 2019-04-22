<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasurementRepository")
 * @ORM\Table(name="measurements")
 */
class MeasurementEntity
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
     * @ORM\Column(type="string", length="50")
     * @Assert\Length(max="50")
     */
    protected $value;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var SourceEntity|null
     * @ORM\ManyToOne(targetEntity="App\Entity\SourceEntity", inversedBy="measurements")
     */
    protected $source;

    /**
     * MeasurementEntity constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * @return SourceEntity|null
     */
    public function getSource(): ?SourceEntity
    {
        return $this->source;
    }

    /**
     * @param SourceEntity $source
     */
    public function setSource(SourceEntity $source)
    {
        $this->source = $source;
    }
}
