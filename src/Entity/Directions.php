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

    #[ORM\Column]
    private ?bool $delete = null;

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

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }
}
