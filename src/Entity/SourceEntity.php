<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 * @ORM\Table(name="sources")
 */
class SourceEntity
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
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max="50")
     */
    protected $unit;

    /**
     * @var GroupEntity|null
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupEntity", inversedBy="sources")
     */
    protected $group;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\MeasurementEntity", mappedBy="source")
     */
    protected $measurements;

    /**
     * SourceEntity constructor.
     */
    public function __construct()
    {
        $this->measurements = new ArrayCollection();
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
