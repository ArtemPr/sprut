<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrainingCentersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingCentersRepository::class)]
class TrainingCenters
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $url;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $external_upload_bakalavrmagistr_id;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $external_upload_sdo_id;

    #[ORM\OneToMany(mappedBy: 'training_centre', targetEntity: TrainingCentersRequisites::class)]
    private $trainingCentersRequisites;

    public function __construct()
    {
        $this->trainingCentersRequisites = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExternalUploadBakalavrmagistrId()
    {
        return $this->external_upload_bakalavrmagistr_id;
    }

    /**
     * @param mixed $external_upload_bakalavrmagistr_id
     */
    public function setExternalUploadBakalavrmagistrId($external_upload_bakalavrmagistr_id): void
    {
        $this->external_upload_bakalavrmagistr_id = $external_upload_bakalavrmagistr_id;
    }

    public function getExternalUploadSdoId(): ?string
    {
        return $this->external_upload_sdo_id;
    }

    /**
     * @return $this
     */
    public function setExternalUploadSdoId(?string $external_upload_sdo_id): self
    {
        $this->external_upload_sdo_id = $external_upload_sdo_id;

        return $this;
    }

    /**
     * @return Collection<int, TrainingCentersRequisites>
     */
    public function getTrainingCentersRequisites(): Collection
    {
        return $this->trainingCentersRequisites;
    }

    public function addTrainingCentersRequisite(TrainingCentersRequisites $trainingCentersRequisite): self
    {
        if (!$this->trainingCentersRequisites->contains($trainingCentersRequisite)) {
            $this->trainingCentersRequisites[] = $trainingCentersRequisite;
            $trainingCentersRequisite->setTrainingCentre($this);
        }

        return $this;
    }

    public function removeTrainingCentersRequisite(TrainingCentersRequisites $trainingCentersRequisite): self
    {
        if ($this->trainingCentersRequisites->removeElement($trainingCentersRequisite)) {
            // set the owning side to null (unless already changed)
            if ($trainingCentersRequisite->getTrainingCentre() === $this) {
                $trainingCentersRequisite->setTrainingCentre(null);
            }
        }

        return $this;
    }
}
