<?php

namespace App\Entity;

use App\Repository\ProfStandartsActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfStandartsActivitiesRepository::class)]
class ProfStandartsActivities
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(inversedBy: 'profStandartsActivities', targetEntity: ProfStandarts::class)]
    private $prof_standart_id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\OneToMany(mappedBy: 'profstandart_activities', targetEntity: ProfStandartsCompetences::class)]
    private $profStandartsCompetences;

    public function __construct()
    {
        $this->prof_standart_id = new ArrayCollection();
        $this->profStandartsCompetences = new ArrayCollection();
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
     * @param ArrayCollection $prof_standart_id
     */
    public function setProfStandartId($prof_standart_id): void
    {
        $this->prof_standart_id = $prof_standart_id;
    }

    /**
     * @return Collection<int, ProfStandarts>
     */
    public function getProfStandartId(): Collection
    {
        return $this->prof_standart_id;
    }

    public function addProfStandartId(ProfStandarts $profStandartId): self
    {
        if (!$this->prof_standart_id->contains($profStandartId)) {
            $this->prof_standart_id[] = $profStandartId;
            $profStandartId->setProfStandartsActivities($this);
        }

        return $this;
    }

    public function removeProfStandartId(ProfStandarts $profStandartId): self
    {
        if ($this->prof_standart_id->removeElement($profStandartId)) {
            // set the owning side to null (unless already changed)
            if ($profStandartId->getProfStandartsActivities() === $this) {
                $profStandartId->setProfStandartsActivities(null);
            }
        }

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, ProfStandartsCompetences>
     */
    public function getProfStandartsCompetences(): Collection
    {
        return $this->profStandartsCompetences;
    }

    public function addProfStandartsCompetence(ProfStandartsCompetences $profStandartsCompetence): self
    {
        if (!$this->profStandartsCompetences->contains($profStandartsCompetence)) {
            $this->profStandartsCompetences[] = $profStandartsCompetence;
            $profStandartsCompetence->setProfstandartActivities($this);
        }

        return $this;
    }

    public function removeProfStandartsCompetence(ProfStandartsCompetences $profStandartsCompetence): self
    {
        if ($this->profStandartsCompetences->removeElement($profStandartsCompetence)) {
            // set the owning side to null (unless already changed)
            if ($profStandartsCompetence->getProfstandartActivities() === $this) {
                $profStandartsCompetence->setProfstandartActivities(null);
            }
        }

        return $this;
    }
}
