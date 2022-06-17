<?php

namespace App\Entity;

use App\Repository\DepartamentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartamentRepository::class)]
class Departament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'departament')]
    private $user_departament;

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

    public function getUserDepartament(): ?User
    {
        return $this->user_departament;
    }

    public function setUserDepartament(?User $user_departament): self
    {
        $this->user_departament = $user_departament;

        return $this;
    }
}
