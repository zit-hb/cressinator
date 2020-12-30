<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasurementSourceRepository")
 * @ORM\Table(name="measurement_sources")
 */
class MeasurementSourceEntity implements EntityInterface
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
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max="50")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max="50")
     * @Assert\NotBlank()
     */
    protected $unit;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'c'>")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'c'>")
     */
    protected $updatedAt;

    /**
     * @var GroupEntity|null
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupEntity", inversedBy="sources")
     * @Assert\NotNull()
     */
    protected $group;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\MeasurementEntity", mappedBy="source")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     * @Serializer\Exclude()
     */
    protected $measurements;

    /**
     * MeasurementSourceEntity constructor.
     */
    public function __construct()
    {
        $this->measurements = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit)
    {
        $this->unit = $unit;
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
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return GroupEntity|null
     */
    public function getGroup(): ?GroupEntity
    {
        return $this->group;
    }

    /**
     * @param GroupEntity $group
     */
    public function setGroup(GroupEntity $group)
    {
        $this->group = $group;
    }

    /**
     * @return Collection
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    /**
     * @param Collection $measurements
     */
    public function setMeasurements(Collection $measurements)
    {
        $this->measurements = $measurements;
    }

    /**
     * @param MeasurementEntity $measurement
     */
    public function addMeasurement(MeasurementEntity $measurement)
    {
        $this->measurements[] = $measurement;
    }

    /**
     * @param MeasurementEntity $measurement
     */
    public function removeMeasurement(MeasurementEntity $measurement)
    {
        $this->measurements->remove($measurement);
    }
}
