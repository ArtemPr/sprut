<?php

namespace App\Entity;

use App\Repository\TrainingCentersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingCentersRepository::class)]
class TrainingCenters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: MasterProgram::class, mappedBy: 'training_center')]
    private $masterPrograms;

    public function __construct()
    {
        $this->masterPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, MasterProgram>
     */
    public function getMasterPrograms(): Collection
    {
        return $this->masterPrograms;
    }

    public function addMasterProgram(MasterProgram $masterProgram): self
    {
        if (!$this->masterPrograms->contains($masterProgram)) {
            $this->masterPrograms[] = $masterProgram;
            $masterProgram->addTrainingCenter($this);
        }

        return $this;
    }

    public function removeMasterProgram(MasterProgram $masterProgram): self
    {
        if ($this->masterPrograms->removeElement($masterProgram)) {
            $masterProgram->removeTrainingCenter($this);
        }

        return $this;
    }
}
