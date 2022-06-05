<?php

namespace App\Entity;

use App\Repository\DisciplineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisciplineRepository::class)]
class Discipline
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $type;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $comment;

    #[ORM\Column(type: 'text', nullable: true)]
    private $purpose;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $docx_testing_file_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $docx_old_doc_file_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $practice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $practicum_flag;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $status;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(?string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getDocxTestingFileName(): ?string
    {
        return $this->docx_testing_file_name;
    }

    public function setDocxTestingFileName(?string $docx_testing_file_name): self
    {
        $this->docx_testing_file_name = $docx_testing_file_name;

        return $this;
    }

    public function getDocxOldDocFileName(): ?string
    {
        return $this->docx_old_doc_file_name;
    }

    public function setDocxOldDocFileName(?string $docx_old_doc_file_name): self
    {
        $this->docx_old_doc_file_name = $docx_old_doc_file_name;

        return $this;
    }

    public function getPractice(): ?string
    {
        return $this->practice;
    }

    public function setPractice(?string $practice): self
    {
        $this->practice = $practice;

        return $this;
    }

    public function getPracticumFlag(): ?string
    {
        return $this->practicum_flag;
    }

    public function setPracticumFlag(?string $practicum_flag): self
    {
        $this->practicum_flag = $practicum_flag;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
