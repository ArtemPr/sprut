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
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'federalStandartCompetencies', targetEntity: FederalStandart::class)]
    private $federal_standart;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    public function __construct()
    {
        $this->federal_standart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
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
}
