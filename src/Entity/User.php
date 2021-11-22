<?php

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
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
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
    private string $username;

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
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Wine", mappedBy="user")
     */
    private Collection $wines;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Category", mappedBy="user")
     */
    private Collection $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Grape", mappedBy="user")
     */
    private Collection $grapes;

    public function __construct()
    {
        $this->wines = new ArrayCollection();
        $this->grapes = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection
     */
    public function getWines(): Collection
    {
        return $this->wines;
    }

    /**
     * @param Collection $wines
     */
    public function setWines(Collection $wines): void
    {
        $this->wines = $wines;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection $categories
     */
    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return Collection
     */
    public function getGrapes(): Collection
    {
        return $this->grapes;
    }

    /**
     * @return Collection
     */
    public function getRedGrapes(): Collection
    {
        $grapes = new ArrayCollection();
        foreach ($this->grapes as $grape) {
            if ($grape->getType() === 'red') {
                $grapes->add($grape);
            }
        }
        return $grapes;
    }

    /**
     * @return Collection
     */
    public function getWhiteGrapes(): Collection
    {
        $grapes = new ArrayCollection();
        foreach ($this->grapes as $grape) {
            if ($grape->getType() === 'white') {
                $grapes->add($grape);
            }
        }
        return $grapes;
    }

    /**
     * @param Collection $grapes
     */
    public function setGrapes(Collection $grapes): void
    {
        $this->grapes = $grapes;
    }
}
