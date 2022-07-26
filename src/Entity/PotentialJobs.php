<?php

namespace App\Entity;

use App\Repository\PotentialJobsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PotentialJobsRepository::class)]
class PotentialJobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $jobs_name;

    #[ORM\Column(type: 'text')]
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobsName(): ?string
    {
        return $this->jobs_name;
    }

    public function setJobsName(string $jobs_name): self
    {
        $this->jobs_name = $jobs_name;

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
}
