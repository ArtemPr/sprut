<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $activity = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $surname;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $patronymic;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fullname;

    #[ORM\ManyToOne(inversedBy: 'user_city', targetEntity: City::class)]
    private $city;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $phone;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $skype;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $avatar;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 100, nullable: true, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private $apiHash;

    #[ORM\ManyToOne(inversedBy: 'user_departament', targetEntity: Departament::class)]
    private $departament;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $position;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $delete;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $last_page;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fix_delete;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleted_at = null;

    public function __construct()
    {
        $this->departament = new ArrayCollection();
        $this->city = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiHash()
    {
        return $this->apiHash;
    }

    /**
     * @param mixed $apiHash
     */
    public function setApiHash($apiHash): void
    {
        $this->apiHash = $apiHash;
    }

    public function isActivity(): bool
    {
        return $this->activity;
    }

    public function setActivity(bool $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param mixed $patronymic
     */
    public function setPatronymic($patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype): void
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __get(string $param): string
    {
        return '';
    }

    public function __set(string $param, $value)
    {
        if (!empty($param) && 'passwordNew' == $param && !empty($value)) {
            $this->password = password_hash($value, PASSWORD_DEFAULT);
            $this->setApiHash(md5($this->getName().$this->getEmail().$value));
        }
    }

    /**
     * @return Collection<int, Departament>
     */
    public function getDepartament(): Collection
    {
        return $this->departament;
    }

    /**
     * @param ArrayCollection $departament
     */
    public function setDepartament($departament): void
    {
        $this->departament = $departament;
    }

    public function addDepartament(Departament $departament): self
    {
        if (!$this->departament->contains($departament)) {
            $this->departament[] = $departament;
            $departament->setUserDepartament($this);
        }

        return $this;
    }

    public function removeDepartament(Departament $departament): self
    {
        if ($this->departament->removeElement($departament)) {
            // set the owning side to null (unless already changed)
            if ($departament->getUserDepartament() === $this) {
                $departament->setUserDepartament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCity(): Collection
    {
        return $this->city;
    }

    /**
     * @param ArrayCollection $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function addCity(City $City): self
    {
        if (!$this->city->contains($City)) {
            $this->city[] = $City;
            $City->setUserCity($this);
        }

        return $this;
    }

    public function removeCity(City $City): self
    {
        if ($this->city->removeElement($City)) {
            // set the owning side to null (unless already changed)
            if ($City->getUserCity() === $this) {
                $City->setUserCity(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
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
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getLastPage()
    {
        return $this->last_page;
    }

    /**
     * @param mixed $last_page
     */
    public function setLastPage($last_page): void
    {
        $this->last_page = $last_page;
    }

    /**
     * @return mixed
     */
    public function getFixDelete()
    {
        return $this->fix_delete;
    }

    /**
     * @param mixed $fix_delete
     */
    public function setFixDelete($fix_delete): void
    {
        $this->fix_delete = $fix_delete;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

}
