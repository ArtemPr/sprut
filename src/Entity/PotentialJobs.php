<?php

namespace App\Entity;

use App\Repository\PotentialJobsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PotentialJobsRepository::class)]
class PotentialJobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $jobs_name;

    #[ORM\Column(type: 'text')]
    private $comment;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $delete;

    #[ORM\ManyToMany(targetEntity: MasterProgram::class, mappedBy: 'potential_jobs')]
    private Collection $masterPrograms;

    public function __construct()
    {
        $this->masterPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobsName(): ?string
    {
        return $this->jobs_name;
    }

    public function setJobsName(string $jobs_name): self
    {
        $this->jobs_name = $jobs_name;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): self
    {
        $this->delete = $delete;

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
            $masterProgram->addPotentialJob($this);
        }

        return $this;
    }

    public function removeMasterProgram(MasterProgram $masterProgram): self
    {
        if ($this->masterPrograms->removeElement($masterProgram)) {
            $masterProgram->removePotentialJob($this);
        }

        return $this;
    }
}
