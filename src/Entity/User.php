<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: "user"),
    ORM\UniqueConstraint(fields: ["email"]),
    UniqueEntity(fields: ["email"], message: "There is already a user with this email."),
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[
        ORM\Id,
        ORM\Column(type: "integer"),
        ORM\GeneratedValue(strategy: "IDENTITY"),
    ]
    private int $id;

    #[
        ORM\Column(type: "string", length: 180),
        Assert\NotBlank,
    ]
    private string $name;

    #[
        ORM\Column(type: "string", length: 180, unique: true),
        Assert\NotBlank,
    ]
    private string $email;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string")]
    private string $password;

    #[ORM\Column(type: "boolean")]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "App\WineBundle\Entity\Wine", cascade: ["remove"])]
    private Collection $wines;

    #[ORM\OneToMany(mappedBy: "author", targetEntity: "Page", cascade: ["remove"])]
    private Collection $pages;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "App\WineBundle\Entity\TasteProfile", cascade: ["remove"])]
    private Collection $tasteProfiles;

    #[Pure] public function __construct()
    {
        $this->wines = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->tasteProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        if ($this->isVerified()) {
            $roles[] = 'ROLE_VERIFIED';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function setPages(Collection $pages): void
    {
        $this->pages = $pages;
    }

    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function setWines(Collection $wines): void
    {
        $this->wines = $wines;
    }

    public function getTasteProfiles(): Collection
    {
        return $this->tasteProfiles;
    }

    public function setTasteProfiles(Collection $tasteProfiles): void
    {
        $this->tasteProfiles = $tasteProfiles;
    }
}
