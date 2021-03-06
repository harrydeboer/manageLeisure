<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[
    ORM\Entity(repositoryClass: RegionRepository::class),
    ORM\Table(name: "region"),
    ORM\UniqueConstraint(name: "name_unique", columns: ["country_id", "name"]),
]
class Region
{
    #[
        ORM\Id,
        ORM\Column(type: "integer"),
        ORM\GeneratedValue(strategy: "IDENTITY"),
    ]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: "region", targetEntity: "Wine", cascade: ["remove"])]
    private Collection $wines;

    #[ORM\OneToMany(mappedBy: "region", targetEntity: "Subregion", cascade: ["remove"])]
    private Collection $subregions;

    #[
        ORM\ManyToOne(targetEntity: "Country", inversedBy: "regions"),
        ORM\JoinColumn(name: "country_id", referencedColumnName: "id", nullable: false),
    ]
    private Country $country;

    #[Pure] public function __construct()
    {
        $this->wines = new ArrayCollection();
        $this->subregions = new ArrayCollection();
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

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function setWines(Collection $wines): void
    {
        $this->wines = $wines;
    }

    public function getSubregions(): Collection
    {
        return $this->subregions;
    }

    public function setSubregions(Collection $subregions): void
    {
        $this->subregions = $subregions;
    }
}
