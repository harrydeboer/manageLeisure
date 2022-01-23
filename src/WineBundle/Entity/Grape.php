<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\GrapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GrapeRepository::class)]
#[ORM\Table(name: "grape")]
#[ORM\UniqueConstraint(name: "name_unique",fields: ["name"])]
#[UniqueEntity(fields: ["name"], message: "There is already a grape with this name.")]
class Grape
{
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: "string", columnDefinition: "enum('red', 'white', 'rosé') NOT NULL")]
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
