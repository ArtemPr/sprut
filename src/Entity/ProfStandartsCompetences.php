<?php

namespace App\Entity;

use App\Repository\ProfStandartsCompetencesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfStandartsCompetencesRepository::class)]
class ProfStandartsCompetences
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ProfStandartsActivities::class, inversedBy: 'profStandartsCompetences')]
    private $profstandart_activities;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $number;

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

    public function getProfstandartActivities(): ?ProfStandartsActivities
    {
        return $this->profstandart_activities;
    }

    public function setProfstandartActivities(?ProfStandartsActivities $profstandart_activities): self
    {
        $this->profstandart_activities = $profstandart_activities;

        return $this;
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }
}
