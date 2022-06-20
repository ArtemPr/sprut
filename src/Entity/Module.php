<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $comment;

    #[ORM\Column(type: 'text')]
    private $purpose;

    #[ORM\Column(type: 'text')]
    private $practice;

    #[ORM\Column(type: 'string', length: 255)]
    private $docx_old_doc_file_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param mixed $purpose
     */
    public function setPurpose($purpose): void
    {
        $this->purpose = $purpose;
    }

    /**
     * @return mixed
     */
    public function getPractice()
    {
        return $this->practice;
    }

    /**
     * @param mixed $practice
     */
    public function setPractice($practice): void
    {
        $this->practice = $practice;
    }

    /**
     * @return mixed
     */
    public function getDocxOldDocFileName()
    {
        return $this->docx_old_doc_file_name;
    }

    /**
     * @param mixed $docx_old_doc_file_name
     */
    public function setDocxOldDocFileName($docx_old_doc_file_name): void
    {
        $this->docx_old_doc_file_name = $docx_old_doc_file_name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
