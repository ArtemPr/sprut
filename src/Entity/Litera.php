<?php

namespace App\Entity;

use App\Repository\LiteraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LiteraRepository::class)]
class Litera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $file;

    #[ORM\ManyToOne(targetEntity: Discipline::class, inversedBy: 'literas')]
    private $discipline;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $size;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'literas')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\Column(type: 'datetime')]
    private $data_create;

    #[ORM\Column(type: 'text', nullable: true)]
    private $comment;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $doc_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $doc_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $doc_title;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }

    public function setDiscipline(?Discipline $discipline): self
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDataCreate(): ?\DateTimeInterface
    {
        return $this->data_create;
    }

    public function setDataCreate(\DateTimeInterface $data_create): self
    {
        $this->data_create = $data_create;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDocId(): ?int
    {
        return $this->doc_id;
    }

    public function setDocId(?int $doc_id): self
    {
        $this->doc_id = $doc_id;

        return $this;
    }

    public function getDocName(): ?string
    {
        return $this->doc_name;
    }

    public function setDocName(?string $doc_name): self
    {
        $this->doc_name = $doc_name;

        return $this;
    }

    public function getDocTitle(): ?string
    {
        return $this->doc_title;
    }

    public function setDocTitle(?string $doc_title): self
    {
        $this->doc_title = $doc_title;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(?\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }
}
