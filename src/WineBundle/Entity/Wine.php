<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\Country;
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
    private string $labelExtension;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $review;

    /**
     * @ORM\Column(type="integer")
     */
    private int $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="Grape", inversedBy="wines")
     * @ORM\JoinTable(name="wine_grape",
     *     joinColumns={@ORM\JoinColumn(name="wine_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="grape_id", referencedColumnName="id", onDelete="CASCADE")}
     *     )
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "The wine must have at least one grape.",
     * )
     */
    private Collection $grapes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="wines")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="TasteProfile", inversedBy="wines")
     * @ORM\JoinColumn(name="taste_profile_id", referencedColumnName="id", nullable=true)
     */
    private ?TasteProfile $tasteProfile;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="wines")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=false)
     */
    private Region $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="wines")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     */
    private Country $country;

    public function __construct()
    {
        $this->grapes = new ArrayCollection();
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

    public function getLabelExtension(): string
    {
        return $this->labelExtension;
    }

    public function setLabelExtension(string $labelExtension): void
    {
        $this->labelExtension = $labelExtension;
    }

    public function getGrapes(): Collection
    {
        return $this->grapes;
    }

    public function setGrapes(ArrayCollection $grapes): void
    {
        $this->grapes = $grapes;
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

    /**
     * In the view the grapes of a wine are shown as one comma seperated string.
     */
    public function getGrapeNamesAsString(): string
    {
        $text = '';
        foreach($this->grapes as $grape) {
            $text .= $grape->getName() . ', ';
        }
        return rtrim($text, ', ');
    }

    public function getTasteProfile(): ?TasteProfile
    {
        return $this->tasteProfile;
    }

    public function setTasteProfile(?TasteProfile $tasteProfile): void
    {
        $this->tasteProfile = $tasteProfile;
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

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): void
    {
        $this->review = $review;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getLabel(): UploadedFile
    {
        $idString = (string) $this->getId();
        return new UploadedFile($this->getProjectDir() . '/public/' . $this->getLabelPath(),
            $idString . '.' . $this->getLabelExtension());
    }

    public function setLabel(?UploadedFile $label)
    {
        if (!is_null($label)) {
            $this->setLabelExtension($label->getClientOriginalExtension());
        }
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 3);
    }

    public function moveLabel(?UploadedFile $label)
    {
        if (!is_null($label)) {
            $id = (string) $this->getId();
            if (!str_starts_with($label->getMimeType(), 'image/')) {
                throw new ValidatorException('The file is not an image.');
            }
            $extraPath = '';
            if ($_ENV['APP_ENV'] === 'test') {
                $extraPath = 'test/';
            }
            $label->move(
                $this->getProjectDir() . '/public/img/wine/labels/' . $extraPath,
                $id . '.' . $label->getClientOriginalExtension()
            );
        }
    }

    public function getLabelPath(): string
    {
        $idString = (string) $this->getId();
        $extraPath = '';
        if ($_ENV['APP_ENV'] === 'test') {
            $extraPath = 'test/';
        }

        return 'img/wine/labels/' . $extraPath . $idString . '.' . $this->getLabelExtension();
    }

    public function unlinkLabel()
    {
        unlink($this->getProjectDir() . '/public/' . $this->getLabelPath());
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
}
