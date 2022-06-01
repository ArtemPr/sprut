<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

declare(strict_types=1);

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

    #[ORM\OneToMany(mappedBy: 'trainingCentersRequisites', targetEntity: TrainingCenters::class)]
    private $training_centre;

    public function __construct()
    {
        $this->training_centre = new ArrayCollection();
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

    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * @return $this
     */
    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getDirectorPosition(): ?string
    {
        return $this->director_position;
    }

    /**
     * @return $this
     */
    public function setDirectorPosition(string $director_position): self
    {
        $this->director_position = $director_position;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->from_date;
    }

    /**
     * @return $this
     */
    public function setFromDate(?\DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    /**
     * @return $this
     */
    public function setShortName(?string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    /**
     * @return $this
     */
    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, TrainingCenters>
     */
    public function getTrainingCentre(): Collection
    {
        return $this->training_centre;
    }

    /**
     * @return $this
     */
    public function addTrainingCentre(TrainingCenters $trainingCentre): self
    {
        if (!$this->training_centre->contains($trainingCentre)) {
            $this->training_centre[] = $trainingCentre;
            $trainingCentre->setTrainingCentersRequisites($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
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
}
