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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $active;

    #[ORM\OneToMany(targetEntity: FederalStandartCompetencies::class, mappedBy: 'federal_standart')]
    private $federalStandartCompetencies;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_create;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $pr_num;

    #[ORM\Column(type: 'text', nullable: true)]
    private $old_name;

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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): void
    {
        $this->code = $code;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(?\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrNum()
    {
        return $this->pr_num;
    }

    /**
     * @param mixed $pr_num
     */
    public function setPrNum($pr_num): void
    {
        $this->pr_num = $pr_num;
    }

    /**
     * @return mixed
     */
    public function getOldName()
    {
        return $this->old_name;
    }

    /**
     * @param mixed $old_name
     */
    public function setOldName($old_name): void
    {
        $this->old_name = $old_name;
    }

}
