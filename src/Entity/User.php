<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Wine", mappedBy="user")
     */
    private Collection $wines;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\TasteProfile", mappedBy="user")
     */
    private Collection $tasteProfiles;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Grape", mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $grapes;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Region", mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $regions;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Country", mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $countries;

    public function __construct()
    {
        $this->wines = new ArrayCollection();
        $this->grapes = new ArrayCollection();
        $this->tasteProfiles = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->regions = new ArrayCollection();
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

        return array_unique($roles);
    }

    public function addRoles(array $roles): void
    {
        $this->roles = array_unique(array_merge($this->roles, $roles));
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

    public function getGrapes(): Collection
    {
        return $this->grapes;
    }

    public function setGrapes(Collection $grapes): void
    {
        $this->grapes = $grapes;
    }

    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function setRegions(Collection $regions): void
    {
        $this->regions = $regions;
    }

    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function setCountries(Collection $countries): void
    {
        $this->countries = $countries;
    }
}
