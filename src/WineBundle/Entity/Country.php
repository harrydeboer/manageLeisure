<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(
 *    name="country",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_unique", columns={"user_id", "name"})
 *    }
 * )
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="countries")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Region", mappedBy="country", orphanRemoval=true)
     */
    private Collection $regions;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Wine", mappedBy="country", orphanRemoval=true)
     */
    private Collection $wines;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
    }

    public function getId(): int
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

    public function setRegions(Collection $regions): void
    {
        $this->regions = $regions;
    }

    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function setWines(Collection $wines): void
    {
        $this->wines = $wines;
    }
}
