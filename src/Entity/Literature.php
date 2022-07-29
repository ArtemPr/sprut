<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LiteratureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LiteratureRepository::class)]
class Literature
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private $authors;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $year;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $link;

    #[ORM\Column(nullable: true)]
    private ?bool $delete = null;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    /**
     * @return $this
     */
    public function setAuthors(?string $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @return $this
     */
    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @return $this
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

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
}
