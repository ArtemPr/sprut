<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrainingCentersRequisitesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingCentersRequisitesRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    attributes: ['security' => "is_granted('ROLE_API_USER')", 'pagination_items_per_page' => 100],
    paginationEnabled: true
)]
class TrainingCentersRequisites
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(inversedBy: 'training_centre_requisites', targetEntity: TrainingCenters::class)]
    private $training_centre;

    #[ORM\Column(type: 'string', length: 255)]
    private $director;

    #[ORM\Column(type: 'string', length: 255)]
    private $director_position;

    #[ORM\Column(type: 'date', nullable: true)]
    private $from_date;

    #[ORM\Column(type: 'text', nullable: true)]
    private $short_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $full_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\Column(type: 'text', nullable: true)]
    private $address;

    public function __construct()
    {
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
     * @return Collection<int, TrainingCenters>
     */
    public function getTrainingCentre()
    {
        return $this->training_centre;
    }

    /**
     * @param ArrayCollection $training_centre
     */
    public function setTrainingCentre($training_centre): void
    {
        $this->training_centre = $training_centre;
    }

    public function addTrainingCentre(TrainingCenters $trainingCentre): self
    {
        if (!$this->training_centre->contains($trainingCentre)) {
            $this->training_centre[] = $trainingCentre;
            $trainingCentre->setTrainingCentersRequisites($this);
        }

        return $this;
    }

    public function removeTrainingCentre(TrainingCenters $trainingCentre): self
    {
        if ($this->training_centre->removeElement($trainingCentre)) {
            // set the owning side to null (unless already changed)
            if ($trainingCentre->getTrainingCentersRequisites() === $this) {
                $trainingCentre->setTrainingCentersRequisites(null);
            }
        }

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getDirectorPosition(): ?string
    {
        return $this->director_position;
    }

    public function setDirectorPosition(string $director_position): self
    {
        $this->director_position = $director_position;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->from_date;
    }

    public function setFromDate(?\DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

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

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
