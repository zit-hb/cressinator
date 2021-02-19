<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="`groups`")
 */
class GroupEntity implements EntityInterface
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
     * @var Collection
     * @ORM\OneToMany(targetEntity="MeasurementSourceEntity", mappedBy="group")
     * @Serializer\Exclude()
     */
    protected $measurementSources;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="RecordingSourceEntity", mappedBy="group")
     * @Serializer\Exclude()
     */
    protected $recordingSources;

    /**
     * GroupEntity constructor.
     */
    public function __construct()
    {
        $this->measurementSources = new ArrayCollection();
        $this->recordingSources = new ArrayCollection();
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
     * @return Collection
     */
    public function getMeasurementSources(): Collection
    {
        return $this->measurementSources;
    }

    /**
     * @param Collection $measurementSources
     */
    public function setMeasurementSources(Collection $measurementSources)
    {
        $this->measurementSources = $measurementSources;
    }

    /**
     * @param MeasurementSourceEntity $measurementSource
     */
    public function addMeasurementSource(MeasurementSourceEntity $measurementSource)
    {
        $this->measurementSources[] = $measurementSource;
    }

    /**
     * @param MeasurementSourceEntity $measurementSource
     */
    public function removeMeasurementSource(MeasurementSourceEntity $measurementSource)
    {
        $this->measurementSources->remove($measurementSource);
    }

    /**
     * @return Collection
     */
    public function getRecordingSources(): Collection
    {
        return $this->recordingSources;
    }

    /**
     * @param Collection $recordingSources
     */
    public function setRecordingSources(Collection $recordingSources)
    {
        $this->recordingSources = $recordingSources;
    }

    /**
     * @param MeasurementSourceEntity $recordingSource
     */
    public function addRecordingSource(MeasurementSourceEntity $recordingSource)
    {
        $this->recordingSources[] = $recordingSource;
    }

    /**
     * @param MeasurementSourceEntity $recordingSource
     */
    public function removeRecordingSource(MeasurementSourceEntity $recordingSource)
    {
        $this->recordingSources->remove($recordingSource);
    }
}
