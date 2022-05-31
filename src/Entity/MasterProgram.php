<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MasterProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MasterProgramRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    attributes: ['security' => "is_granted('ROLE_API_USER')", 'pagination_items_per_page' => 100],
    paginationEnabled: true
)]
class MasterProgram
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\ManyToMany(targetEntity: TrainingCenters::class, inversedBy: 'masterPrograms')]
    private $training_center;

    #[ORM\Column(type: 'integer')]
    private $length_hour;

    #[ORM\Column(type: 'integer')]
    private $length_week;

    #[ORM\ManyToOne(targetEntity: ProgramType::class, inversedBy: 'program')]
    private $program_type;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $length_week_short;

    public function __construct()
    {
        $this->training_center = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TrainingCenters>
     */
    public function getTrainingCenter(): Collection
    {
        return $this->training_center;
    }

    public function addTrainingCenter(TrainingCenters $trainingCenter): self
    {
        if (!$this->training_center->contains($trainingCenter)) {
            $this->training_center[] = $trainingCenter;
        }

        return $this;
    }

    public function removeTrainingCenter(TrainingCenters $trainingCenter): self
    {
        $this->training_center->removeElement($trainingCenter);

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

    public function setLengthWeekShort(?int $length_week_short): self
    {
        $this->length_week_short = $length_week_short;

        return $this;
    }
}
