<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProgramTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramTypeRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    attributes: ['security' => "is_granted('ROLE_API_USER')", 'pagination_items_per_page' => 100],
    paginationEnabled: true
)]
class ProgramType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameType(): ?string
    {
        return $this->name_type;
    }

    public function setNameType(string $name_type): self
    {
        $this->name_type = $name_type;

        return $this;
    }
}
