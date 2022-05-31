<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrainingCentersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingCentersRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    attributes: ['security' => "is_granted('ROLE_API_USER')", 'pagination_items_per_page' => 100],
    paginationEnabled: true
)]
class TrainingCenters
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: MasterProgram::class, mappedBy: 'training_center')]
    private $masterPrograms;

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


    public function __construct()
    {
        $this->masterPrograms = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, MasterProgram>
     */
    public function getMasterPrograms(): Collection
    {
        return $this->masterPrograms;
    }

    public function addMasterProgram(MasterProgram $masterProgram): self
    {
        if (!$this->masterPrograms->contains($masterProgram)) {
            $this->masterPrograms[] = $masterProgram;
            $masterProgram->addTrainingCenter($this);
        }

        return $this;
    }

    public function removeMasterProgram(MasterProgram $masterProgram): self
    {
        if ($this->masterPrograms->removeElement($masterProgram)) {
            $masterProgram->removeTrainingCenter($this);
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

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

    public function setExternalUploadSdoId(?string $external_upload_sdo_id): self
    {
        $this->external_upload_sdo_id = $external_upload_sdo_id;

        return $this;
    }
}
