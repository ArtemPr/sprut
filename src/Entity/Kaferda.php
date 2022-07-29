<?php

namespace App\Entity;

use App\Repository\KaferdaRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: KaferdaRepository::class)]
class Kaferda
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $director;

    #[ORM\ManyToOne(targetEntity: TrainingCenters::class)]
    private $training_centre;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private $parent;

    #[ORM\ManyToOne(targetEntity: ProductLine::class)]
    private $product_line;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $delete;

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

    public function getDirector(): ?User
    {
        return $this->director;
    }

    public function setDirector(?User $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getTrainingCentre(): ?TrainingCenters
    {
        return $this->training_centre;
    }

    public function setTrainingCentre(?TrainingCenters $training_centre): self
    {
        $this->training_centre = $training_centre;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    public function setProductLine(?ProductLine $productLine): self
    {
        $this->product_line = $productLine;

        return $this;
    }

    public function getProductLine(): ?ProductLine
    {
        return $this->product_line;
    }

    public function setParent($parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): int
    {
        return $this->parent;
    }
}
