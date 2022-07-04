<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProgramTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramTypeRepository::class)]
class ProgramType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $short_name_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNameType()
    {
        return $this->name_type;
    }

    /**
     * @param mixed $name_type
     */
    public function setNameType($name_type): void
    {
        $this->name_type = $name_type;
    }

    /**
     * @return mixed
     */
    public function getShortNameType()
    {
        return $this->short_name_type;
    }

    /**
     * @param mixed $short_name_type
     */
    public function setShortNameType($short_name_type): void
    {
        $this->short_name_type = $short_name_type;
    }

}
