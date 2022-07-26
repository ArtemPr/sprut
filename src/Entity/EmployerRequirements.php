<?php

namespace App\Entity;

use App\Repository\EmployerRequirementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployerRequirementsRepository::class)]
class EmployerRequirements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $requirement_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $comment;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $delete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequirementName(): ?string
    {
        return $this->requirement_name;
    }

    public function setRequirementName(string $requirement_name): self
    {
        $this->requirement_name = $requirement_name;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }
}
