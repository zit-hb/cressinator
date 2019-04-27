<?php

namespace App\Entity;

use DateTime;
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
     * @var string|null
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\File()
     */
    protected $file;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected $fileName;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var GroupEntity|null
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupEntity", inversedBy="recordings")
     * @Assert\NotNull()
     */
    protected $group;

    /**
     * RecordingEntity constructor.
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
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
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
}
