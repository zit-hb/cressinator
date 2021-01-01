<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordingRepository")
 * @ORM\Table(name="recordings")
 */
class RecordingEntity implements EntityInterface
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
     * @Serializer\Type("DateTime<'c'>")
     */
    protected $createdAt;

    /**
     * @var RecordingSourceEntity|null
     * @ORM\ManyToOne(targetEntity="RecordingSourceEntity", inversedBy="recordings")
     * @Assert\NotNull()
     * @Serializer\Exclude()
     */
    protected $source;

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
     * @return string|UploadedFile|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string|UploadedFile $file
     */
    public function setFile($file)
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
     * @return RecordingSourceEntity|null
     */
    public function getSource(): ?RecordingSourceEntity
    {
        return $this->source;
    }

    /**
     * @param RecordingSourceEntity $source
     */
    public function setSource(RecordingSourceEntity $source)
    {
        $this->source = $source;
    }
}
