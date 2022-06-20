<?php

namespace App\Entity;

use App\Repository\FederalStandartCompetenciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FederalStandartCompetenciesRepository::class)]
class FederalStandartCompetencies
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(inversedBy: 'federalStandartCompetencies', targetEntity: FederalStandart::class)]
    private $federal_standart;

    #[ORM\Column(type: 'string', nullable: true, length: 100)]
    private $code;

    #[ORM\Column(type: 'text', nullable: true)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $number;

    public function __construct()
    {
        $this->federal_standart = new ArrayCollection();
    }

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

    /**
     * @param ArrayCollection $federal_standart
     */
    public function setFederalStandart($federal_standart): void
    {
        $this->federal_standart = $federal_standart;
    }

    /**
     * @return Collection<int, FederalStandart>
     */
    public function getFederalStandart(): Collection
    {
        return $this->federal_standart;
    }

    public function addFederalStandart(FederalStandart $federalStandart): self
    {
        if (!$this->federal_standart->contains($federalStandart)) {
            $this->federal_standart[] = $federalStandart;
            $federalStandart->setFederalStandartCompetencies($this);
        }

        return $this;
    }

    public function removeFederalStandart(FederalStandart $federalStandart): self
    {
        if ($this->federal_standart->removeElement($federalStandart)) {
            // set the owning side to null (unless already changed)
            if ($federalStandart->getFederalStandartCompetencies() === $this) {
                $federalStandart->setFederalStandartCompetencies(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }
}
