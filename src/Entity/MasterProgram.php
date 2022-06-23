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

    public function __construct()
    {
        $this->federal_standart = new ArrayCollection();
        $this->federal_standart_competencies = new ArrayCollection();
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

    public function addFederalStandart(FederalStandart $federalStandart): self
    {
        if (!$this->federal_standart->contains($federalStandart)) {
            $this->federal_standart[] = $federalStandart;
        }

        return $this;
    }

    public function removeFederalStandart(FederalStandart $federalStandart): self
    {
        $this->federal_standart->removeElement($federalStandart);

        return $this;
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
}
