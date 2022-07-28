<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MasterProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\True_;

#[ORM\Entity(repositoryClass: MasterProgramRepository::class)]
class MasterProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $global_id;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $active;

    #[ORM\Column(type: 'string', nullable: true)]
    private $status;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $length_hour;

    #[ORM\Column(type: 'integer')]
    private $length_week;

    #[ORM\ManyToOne(targetEntity: ProgramType::class, inversedBy: 'program')]
    private $program_type;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $length_week_short;

    #[ORM\ManyToMany(targetEntity: FederalStandart::class)]
    private $federal_standart;

    #[ORM\ManyToMany(targetEntity: FederalStandartCompetencies::class)]
    private $federal_standart_competencies;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $additional_flag;

    #[ORM\ManyToMany(targetEntity: ProfStandarts::class)]
    private $prof_standarts;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $history;

    #[ORM\ManyToMany(targetEntity: EmployerRequirements::class, inversedBy: 'masterPrograms')]
    private Collection $employer_requirements;

    #[ORM\ManyToMany(targetEntity: PotentialJobs::class, inversedBy: 'masterPrograms')]
    private Collection $potential_jobs;

    #[ORM\ManyToMany(targetEntity: TrainingCenters::class, mappedBy: 'program')]
    private $training_centre;

    public function __construct()
    {
        $this->federal_standart = new ArrayCollection();
        $this->employer_requirements = new ArrayCollection();
        $this->potential_jobs = new ArrayCollection();
        $this->training_centre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getGlobalId()
    {
        return $this->global_id;
    }

    public function setGlobalId($global_id): void
    {
        $this->global_id = $global_id;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active): void
    {
        $this->active = $active;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
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

    public function getLengthHour(): ?int
    {
        return $this->length_hour;
    }

    public function setLengthHour(int $length_hour): self
    {
        $this->length_hour = $length_hour;

        return $this;
    }

    public function getLengthWeek(): ?int
    {
        return $this->length_week;
    }

    function setLengthWeek(int $length_week): self
    {
        $this->length_week = $length_week;

        return $this;
    }

    public function getProgramType()
    {
        return $this->program_type;
    }

    public function setProgramType($program_type): void
    {
        $this->program_type = $program_type;
    }

    public function getLengthWeekShort(): ?int
    {
        return $this->length_week_short;
    }

    public function setLengthWeekShort(?int $length_week_short): self
    {
        $this->length_week_short = $length_week_short;

        return $this;
    }

    public function getFederalStandart(): Collection
    {
        return $this->federal_standart;
    }

    public function getFederalStandartCompetencies(): Collection
    {
        return $this->federal_standart_competencies;
    }

    public function addFederalStandartCompetency(FederalStandartCompetencies $federalStandartCompetency): self
    {
        if (!$this->federal_standart_competencies->contains($federalStandartCompetency)) {
            $this->federal_standart_competencies[] = $federalStandartCompetency;
        }

        return $this;
    }

    public function removeFederalStandartCompetency(FederalStandartCompetencies $federalStandartCompetency): self
    {
        $this->federal_standart_competencies->removeElement($federalStandartCompetency);

        return $this;
    }

    public function isAdditionalFlag(): ?bool
    {
        return $this->additional_flag;
    }

    public function setAdditionalFlag(?bool $additional_flag): self
    {
        $this->additional_flag = $additional_flag;

        return $this;
    }

    public function getProfStandarts(): Collection
    {
        return $this->prof_standarts;
    }

    public function isHistory(): ?bool
    {
        return $this->history;
    }

    public function setHistory(?bool $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function addFederalStandart(FederalStandart $federalStandart): self
    {
        if (!$this->federal_standart->contains($federalStandart)) {
            $this->federal_standart[] = $federalStandart;
        }

        return $this;
    }

    public function setFederalStandartCompetencies($federal_standart_competencies): void
    {
        $this->federal_standart_competencies = $federal_standart_competencies;
    }

    public function setProfStandarts($prof_standarts): void
    {
        $this->prof_standarts = $prof_standarts;
    }

    public function getEmployerRequirements(): Collection
    {
        return $this->employer_requirements;
    }

    public function addEmployerRequirement(EmployerRequirements $employerRequirement): self
    {
        if (!$this->employer_requirements->contains($employerRequirement)) {
            $this->employer_requirements[] = $employerRequirement;
        }

        return $this;
    }

    public function removeEmployerRequirement(EmployerRequirements $employerRequirement): self
    {
        $this->employer_requirements->removeElement($employerRequirement);

        return $this;
    }

    public function getPotentialJobs(): Collection
    {
        return $this->potential_jobs;
    }

    public function addPotentialJob(PotentialJobs $potentialJob): self
    {
        if (!$this->potential_jobs->contains($potentialJob)) {
            $this->potential_jobs[] = $potentialJob;
        }

        return $this;
    }

    public function removePotentialJob(PotentialJobs $potentialJob): self
    {
        $this->potential_jobs->removeElement($potentialJob);

        return $this;
    }

    public function getTrainingCentre(): ArrayCollection
    {
        return $this->training_centre;
    }

    public function addTrainingCentre(TrainingCenters $training_centre): self
    {
        if (!$this->training_centre->contains($training_centre)) {
            $this->program[] = $training_centre;
        }

        return $this;
    }

    public function removeTrainingCentre(TrainingCenters $training_centre): self
    {
        $this->program->removeElement($training_centre);

        return $this;
    }

}
