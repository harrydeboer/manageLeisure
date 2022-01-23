<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\User;
use App\WineBundle\Repository\TasteProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TasteProfileRepository::class)
@ORM\Table(
 *    name="taste_profile",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_unique", columns={"user_id", "name"})
 *    }
 * )
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank ()
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $secondName = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasteProfiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="Wine", mappedBy="tasteProfile")
     */
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

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(?string $secondName): void
    {
        $this->secondName = strip_tags($secondName);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = strip_tags($description);
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
