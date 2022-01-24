<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\GrapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use InvalidArgumentException;

#[
    ORM\Entity(repositoryClass: GrapeRepository::class),
    ORM\Table(name: "grape"),
    ORM\UniqueConstraint(name: "name_unique",fields: ["name"]),
]
class Grape
{
    public const TYPES = ['red', 'white','rosé'];

    #[
        ORM\Id,
        ORM\Column(type: "integer"),
        ORM\GeneratedValue(strategy: "IDENTITY"),
    ]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", nullable: false)]
    private string $type;

    #[ORM\ManyToMany(targetEntity: "Wine", mappedBy: "grapes")]
    private Collection $wines;

    #[Pure] public function __construct()
    {
        $this->wines = new ArrayCollection();
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException("Invalid type.");
        }
        $this->type = $type;
    }

    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function setWines(ArrayCollection $wines): void
    {
        $this->wines = $wines;
    }

    public function addWine(Wine $wine)
    {
        if ($this->wines->contains($wine)) {
            return;
        }

        $this->wines->add($wine);
        $wine->addGrape($this);
    }

    public function removeWine(Wine $wine)
    {
        if (!$this->wines->contains($wine)) {
            return;
        }

        $this->wines->removeElement($wine);
        $wine->removeGrape($this);
    }
}
