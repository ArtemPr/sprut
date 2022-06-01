<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MasterProgramRepository;
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

    #[ORM\Column(type: 'integer')]
    private $length_hour;

    #[ORM\Column(type: 'integer')]
    private $length_week;

    #[ORM\ManyToOne(targetEntity: ProgramType::class, inversedBy: 'program')]
    private $program_type;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $length_week_short;

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
}
