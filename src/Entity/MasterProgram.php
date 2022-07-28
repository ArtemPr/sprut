<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MasterProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MasterProgramRepository::class)]
class MasterProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

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

    #[ORM\ManyToMany(targetEntity: TrainingCenters::class)]
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

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLengthHour(): ?int
    {
        return $this->length_hour;
    }

    /**
     * @return $this
     */
    public function setLengthHour(int $length_hour): self
    {
        $this->length_hour = $length_hour;

        return $this;
    }

    public function getLengthWeek(): ?int
    {
        return $this->length_week;
    }

    /**
     * @return $this
     */
    public function setLengthWeek(int $length_week): self
    {
        $this->length_week = $length_week;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProgramType()
    {
        return $this->program_type;
    }

    /**
     * @param mixed $program_type
     */
    public function setProgramType($program_type): void
    {
        $this->program_type = $program_type;
    }

    public function getLengthWeekShort(): ?int
    {
        return $this->length_week_short;
    }

    /**
     * @return $this
     */
    public function setLengthWeekShort(?int $length_week_short): self
    {
        $this->length_week_short = $length_week_short;

        return $this;
    }

    /**
     * @return Collection<int, FederalStandart>
     */
    public function getFederalStandart(): Collection
    {
        return $this->federal_standart;
    }

    /**
     * @return Collection<int, FederalStandartCompetencies>
     */
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

    /**
     * @return Collection<int, ProfStandarts>
     */
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

    /**
     * @param $federal_standart
     */
    public function addFederalStandart(FederalStandart $federalStandart): self
    {
    if (!$this->federal_standart->contains($federalStandart)) {
        $this->federal_standart[] = $federalStandart;
    }

    return $this;
}

    /**
     * @param $federal_standart_competencies
     */
    public function setFederalStandartCompetencies($federal_standart_competencies): void
    {
        $this->federal_standart_competencies = $federal_standart_competencies;
    }

    /**
     * @param $prof_standarts
     */
    public function setProfStandarts($prof_standarts): void
    {
        $this->prof_standarts = $prof_standarts;
    }

    /**
     * @return Collection<int, EmployerRequirements>
     */
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

    /**
     * @return Collection<int, PotentialJobs>
     */
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

    /**
     * @return ArrayCollection
     */
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
