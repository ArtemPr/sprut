<?php

namespace App\Entity;

use App\Repository\KaferdaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KaferdaRepository::class)]
class Kaferda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $director;

    #[ORM\ManyToOne(targetEntity: TrainingCenters::class)]
    private $training_centre;

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

    public function getDirector(): ?User
    {
        return $this->director;
    }

    public function setDirector(?User $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getTrainingCentre(): ?TrainingCenters
    {
        return $this->training_centre;
    }

    public function setTrainingCentre(?TrainingCenters $training_centre): self
    {
        $this->training_centre = $training_centre;

        return $this;
    }
}
