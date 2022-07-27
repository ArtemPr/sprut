<?php

namespace App\Entity;

use App\Repository\DirectionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectionsRepository::class)]
class Directions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $direction_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDirectionName(): ?string
    {
        return $this->direction_name;
    }

    public function setDirectionName(string $direction_name): self
    {
        $this->direction_name = $direction_name;

        return $this;
    }
}
