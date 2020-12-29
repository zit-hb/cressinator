<?php

namespace App\Entity;

use Cassandra\Date;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
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
     * @ORM\OneToMany(targetEntity="App\Entity\SourceEntity", mappedBy="group")
     * @Serializer\Exclude()
     */
    protected $sources;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\RecordingEntity", mappedBy="group")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     * @Serializer\Exclude()
     */
    protected $recordings;

    /**
     * GroupEntity constructor.
     */
    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->recordings = new ArrayCollection();
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
    public function getSources(): Collection
    {
        return $this->sources;
    }

    /**
     * @param Collection $sources
     */
    public function setSources(Collection $sources)
    {
        $this->sources = $sources;
    }

    /**
     * @param SourceEntity $source
     */
    public function addSource(SourceEntity $source)
    {
        $this->sources[] = $source;
    }

    /**
     * @param SourceEntity $source
     */
    public function removeSource(SourceEntity $source)
    {
        $this->sources->remove($source);
    }

    /**
     * @return Collection
     */
    public function getRecordings(): Collection
    {
        return $this->recordings;
    }

    /**
     * @param Collection $recordings
     */
    public function setRecordings(Collection $recordings)
    {
        $this->recordings = $recordings;
    }

    /**
     * @param RecordingEntity $recording
     */
    public function addRecording(RecordingEntity $recording)
    {
        $this->recordings[] = $recording;
    }

    /**
     * @param RecordingEntity $recording
     */
    public function removeRecording(RecordingEntity $recording)
    {
        $this->recordings->remove($recording);
    }
}
