<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $roles_alt;

    #[ORM\Column(type: 'text', nullable: true)]
    private $auth_list;

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

    public function getRolesAlt(): ?string
    {
        return $this->roles_alt;
    }

    public function setRolesAlt(string $roles_alt): self
    {
        $this->roles_alt = $roles_alt;

        return $this;
    }

    public function getAuthList(): ?string
    {
        return $this->auth_list;
    }

    public function setAuthList(?string $auth_list): self
    {
        $this->auth_list = $auth_list;

        return $this;
    }
}
