<?php

namespace App\Entity;

use App\Repository\FederalStandartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FederalStandartRepository::class)]
class FederalStandart
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $short_name;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $active;

    #[ORM\OneToMany(targetEntity: FederalStandartCompetencies::class, mappedBy: 'federal_standart')]
    private $federalStandartCompetencies;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getShortName()
    {
        return $this->short_name;
    }

    public function setShortName($short_name): void
    {
        $this->short_name = $short_name;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getFederalStandartCompetencies(): ?FederalStandartCompetencies
    {
        return $this->federalStandartCompetencies;
    }

    public function setFederalStandartCompetencies(?FederalStandartCompetencies $federalStandartCompetencies): self
    {
        $this->federalStandartCompetencies = $federalStandartCompetencies;

        return $this;
    }
}
