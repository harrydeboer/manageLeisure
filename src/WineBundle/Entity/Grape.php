<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\User;
use App\WineBundle\Repository\GrapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GrapeRepository::class)
 * @ORM\Table(
 *    name="grape",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_unique", columns={"user_id", "name"})
 *    }
 * )
 */
class Grape
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
     * @ORM\Column(type="string", columnDefinition="enum('red', 'white') NOT NULL")
     */
    private string $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="grapes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToMany(targetEntity="Wine", mappedBy="grapes")
     */
    private Collection $wines;

    public function __construct()
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
