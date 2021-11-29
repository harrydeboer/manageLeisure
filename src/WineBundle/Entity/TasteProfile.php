<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\User;
use App\WineBundle\Repository\TasteProfileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TasteProfileRepository::class)
 * @ORM\Table(name="taste_profile")
 * @UniqueEntity("name")
 */
class TasteProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank ()
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasteProfiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="Wine", mappedBy="tasteProfile")
     */
    private Collection $wines;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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
}
