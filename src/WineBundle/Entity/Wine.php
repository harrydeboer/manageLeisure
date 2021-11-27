<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\User;
use App\WineBundle\Repository\WineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @ORM\Entity(repositoryClass=WineRepository::class)
 * @ORM\Table(name="wine")
 */
class Wine
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
     * @ORM\Column(type="string", length=255)
     */
    private string $imageExtension;

    /**
     * @ORM\ManyToMany(targetEntity="Grape", inversedBy="wines")
     * @ORM\JoinTable(name="wine_grape",
     *     joinColumns={@ORM\JoinColumn(name="wine_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="grape_id", referencedColumnName="id", onDelete="CASCADE")}
     *     )
     */
    private Collection $grapes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="wines")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="wines")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private ?Category $category;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(4)
     */
    private int $year;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(10)
     * @Assert\LessThanOrEqual(100)
     */
    private int $rating;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private int $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private int $createdAt;

    public function __construct()
    {
        $this->grapes = new ArrayCollection();
    }

    public function addGrape(Grape $grape)
    {
        if ($this->grapes->contains($grape)) {
            return;
        }

        $this->grapes->add($grape);
        $grape->addWine($this);
    }

    public function removeGrape(Grape $grape)
    {
        if (!$this->grapes->contains($grape)) {
            return;
        }

        $this->grapes->removeElement($grape);
        $grape->removeWine($this);
    }

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

    public function getImageExtension(): string
    {
        return $this->imageExtension;
    }

    public function setImageExtension(string $imageExtension): void
    {
        $this->imageExtension = $imageExtension;
    }

    public function getGrapes(): Collection
    {
        return $this->grapes;
    }

    public function setGrapes(ArrayCollection $grapes): void
    {
        $this->grapes = $grapes;
    }

    public function getGrapeNamesAsString(): string
    {
        $text = '';
        foreach($this->grapes as $grape) {
            $text .= $grape->getName() . ', ';
        }
        return rtrim($text, ', ');
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getPrice(): float
    {
        return $this->price / 100;
    }

    public function setPrice(float $price): void
    {
        $this->price = (int) ($price * 100);
    }

    public function getRating(): float
    {
        return $this->rating / 10;
    }

    public function setRating(float $rating): void
    {
        $this->rating = (int) ($rating * 10);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCreateAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getImage(): UploadedFile
    {
        $idString = (string) $this->getId();
        return new UploadedFile($this->getProjectDir() . '/public/' .
            $this->getLabel(), $idString . '.' . $this->getImageExtension());
    }

    public function setImage(?UploadedFile $image)
    {
        if (!is_null($image)) {
            $this->setImageExtension($image->getClientOriginalExtension());
        }
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 3);
    }

    public function moveImage(?UploadedFile $image)
    {
        if (!is_null($image)) {
            $id = (string) $this->getId();
            if (!str_starts_with($image->getMimeType(), 'image/')) {
                throw new ValidatorException('The file is not an image.');
            }
            $image->move(
                $this->getProjectDir() . '/public/img/labels/',
                $id . '.' . $image->getClientOriginalExtension()
            );
        }
    }

    public function getLabel(): string
    {
        $idString = (string) $this->getId();
        $path = 'img/labels/' . $idString . '.' . $this->getImageExtension();

        return $path;
    }

    public function unlinkImage()
    {
        unlink($this->getProjectDir() . '/public/' . $this->getLabel());
    }
}
