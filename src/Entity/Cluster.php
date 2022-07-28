<?php

namespace App\Entity;

use App\Repository\ClusterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClusterRepository::class)]
class Cluster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'cluster_id', targetEntity: ProductLine::class)]
    private Collection $productLines;

    public function __construct()
    {
        $this->productLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, ProductLine>
     */
    public function getProductLines(): Collection
    {
        return $this->productLines;
    }

    public function addProductLine(ProductLine $productLine): self
    {
        if (!$this->productLines->contains($productLine)) {
            $this->productLines[] = $productLine;
            $productLine->setClusterId($this);
        }

        return $this;
    }

    public function removeProductLine(ProductLine $productLine): self
    {
        if ($this->productLines->removeElement($productLine)) {
            // set the owning side to null (unless already changed)
            if ($productLine->getClusterId() === $this) {
                $productLine->setClusterId(null);
            }
        }

        return $this;
    }
}
