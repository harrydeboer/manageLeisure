<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\WineBundle\Repository\SubregionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SubregionRepository::class)
 * @ORM\Table(
 *    name="subregion",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_unique", columns={"region_id", "name"})
 *    }
 * )
 */
class Subregion
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
     * @ORM\OneToMany(targetEntity="Wine", mappedBy="subregion", cascade={"remove"})
     */
    private Collection $wines;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="subregions")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=false)
     */
    private Region $region;

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

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
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
