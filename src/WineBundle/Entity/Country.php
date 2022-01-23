<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(
 *    name="country",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_unique", columns={"name"})
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
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Region", mappedBy="country", cascade={"remove"})
     */
    private Collection $regions;

    /**
     * @ORM\OneToMany(targetEntity="App\WineBundle\Entity\Wine", mappedBy="country", cascade={"remove"})
     */
    private Collection $wines;

    #[Pure] public function __construct()
    {
        $this->wines = new ArrayCollection();
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
        $this->name = strip_tags($name);
    }

    public function setRegions(Collection $regions): void
    {
        $this->regions = $regions;
    }

    public function getRegions(): Collection
    {
        return $this->regions;
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
