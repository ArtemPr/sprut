<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $activity = false;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $roles_alt;

    #[ORM\Column(type: 'text', nullable: true)]
    private $auth_list;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $delete = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private $comment;

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
     * @return mixed
     */
    public function getRolesAlt()
    {
        return $this->roles_alt;
    }

    /**
     * @param mixed $roles_alt
     */
    public function setRolesAlt($roles_alt): void
    {
        $this->roles_alt = $roles_alt;
    }

    public function getAuthList(): ?string
    {
        return $this->auth_list;
    }

    public function setAuthList(?string $auth_list): self
    {
        $this->auth_list = $auth_list;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivity(): bool
    {
        return $this->activity;
    }

    /**
     * @param bool $activity
     */
    public function setActivity(bool $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param mixed $delete
     */
    public function setDelete($delete): void
    {
        $this->delete = $delete;
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

}
