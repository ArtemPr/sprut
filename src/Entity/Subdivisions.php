<?php

namespace App\Entity;

use App\Repository\SubdivisionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubdivisionsRepository::class)]
class Subdivisions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $subdivisions_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubdivisionsName(): ?string
    {
        return $this->subdivisions_name;
    }

    public function setSubdivisionsName(string $subdivisions_name): self
    {
        $this->subdivisions_name = $subdivisions_name;

        return $this;
    }
}
