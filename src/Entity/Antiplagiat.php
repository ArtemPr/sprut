<?php

namespace App\Entity;

use App\Repository\AntiplagiatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AntiplagiatRepository::class)]
class Antiplagiat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $status;

    #[ORM\Column(type: 'string', length: 255)]
    private $file;

    #[ORM\ManyToOne(targetEntity: Discipline::class)]
    private $discipline;

    #[ORM\Column(type: 'integer')]
    private $size;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $author;

    #[ORM\Column(type: 'datetime')]
    private $data_create;

    #[ORM\Column(type: 'text', nullable: true)]
    private $comment;

    #[ORM\Column(type: 'float', nullable: true)]
    private $plagiat_percent;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $result_file;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $result_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
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

    public function setSize(int $size): self
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

    public function getPlagiatPercent(): ?float
    {
        return $this->plagiat_percent;
    }

    public function setPlagiatPercent(?float $plagiat_percent): self
    {
        $this->plagiat_percent = $plagiat_percent;

        return $this;
    }

    public function getResultFile(): ?string
    {
        return $this->result_file;
    }

    public function setResultFile(?string $result_file): self
    {
        $this->result_file = $result_file;

        return $this;
    }

    public function getResultDate(): ?\DateTimeInterface
    {
        return $this->result_date;
    }

    public function setResultDate(?\DateTimeInterface $result_date): self
    {
        $this->result_date = $result_date;

        return $this;
    }
}
