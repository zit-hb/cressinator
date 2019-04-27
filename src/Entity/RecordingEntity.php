<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordingRepository")
 * @ORM\Table(name="recordings")
 */
class RecordingEntity
{
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\File()
     */
    protected $file;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\SourceEntity", inversedBy="recordings")
     * @Assert\NotNull()
     * @Serializer\Exclude()
     */
    protected $sources;

    /**
     * RecordingEntity constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->sources = new ArrayCollection();
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
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile(string $file)
    {
        $this->file = $file;
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
}
