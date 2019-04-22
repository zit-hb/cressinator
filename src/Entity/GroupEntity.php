<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class GroupEntity
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
    protected $name;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\SourceEntity", mappedBy="group")
     */
    protected $sources;

    /**
     * GroupEntity constructor.
     */
    public function __construct()
    {
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
    public function getName()
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
