<?php

namespace App\Entity;

use App\Repository\DocumentTemplatesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentTemplatesRepository::class)]
class DocumentTemplates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'string', length: 255)]
    private $template_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $file_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $file_size;

    #[ORM\Column(type: 'string', length: 255)]
    private $link;

    #[ORM\Column(type: 'string', length: 255)]
    private $comment;

    #[ORM\Column(type: 'datetime')]
    private $date_create;

    #[ORM\Column(type: 'string', length: 255)]
    private $author;

    #[ORM\Column(type: 'boolean')]
    private $delete;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_change;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTemplateName(): ?string
    {
        return $this->template_name;
    }

    public function setTemplateName(string $template_name): self
    {
        $this->template_name = $template_name;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getFileSize(): ?string
    {
        return $this->file_size;
    }

    public function setFileSize(string $file_size): self
    {
        $this->file_size = $file_size;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    public function getDateChange(): ?\DateTimeInterface
    {
        return $this->date_change;
    }

    public function setDateChange(?\DateTimeInterface $date_change): self
    {
        $this->date_change = $date_change;

        return $this;
    }
}
