<?php

namespace App\Entity;

use App\Repository\ProfStandartsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfStandartsRepository::class)]
class ProfStandarts
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $short_name;

    #[ORM\Column(type: 'boolean')]
    private $archive_flag;

    #[ORM\OneToMany(targetEntity: ProfStandartsActivities::class, mappedBy: 'prof_standart_id')]
    #[ORM\JoinColumn(nullable: false)]
    private $profStandartsActivities;

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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(?string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function isArchiveFlag(): ?bool
    {
        return $this->archive_flag;
    }

    public function setArchiveFlag(bool $archive_flag): self
    {
        $this->archive_flag = $archive_flag;

        return $this;
    }

    public function getProfStandartsActivities(): ?ProfStandartsActivities
    {
        return $this->profStandartsActivities;
    }

    public function setProfStandartsActivities(?ProfStandartsActivities $profStandartsActivities): self
    {
        $this->profStandartsActivities = $profStandartsActivities;

        return $this;
    }
}
