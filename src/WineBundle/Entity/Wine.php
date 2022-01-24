<?php

declare(strict_types=1);

namespace App\WineBundle\Entity;

use App\Entity\User;
use App\WineBundle\Repository\WineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidatorException;

#[
    ORM\Entity(repositoryClass: WineRepository::class),
    ORM\Table(name: "wine"),
]
class Wine
{
    #[
        ORM\Id,
        ORM\Column(type: "integer"),
        ORM\GeneratedValue(strategy: "IDENTITY"),
    ]
    private int $id;

    #[
        ORM\Column(type: "string", length: 255),
        Assert\NotBlank,
    ]
    private string $name;

    #[ORM\Column(type: "wine_type", nullable: false)]
    private string $type;

    #[ORM\Column(type: "string", length: 255)]
    private string $labelExtension;

    #[
        ORM\Column(type: "integer", nullable: true),
        Assert\Length(4),
    ]
    private ?int $year = null;

    #[
        ORM\Column(type: "integer"),
        Assert\GreaterThanOrEqual(10),
        Assert\LessThanOrEqual(100),
    ]
    private int $rating;

    #[
        ORM\Column(type: "integer"),
        Assert\GreaterThanOrEqual(0),
    ]
    private int $price;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $review = null;

    #[ORM\Column(type: "integer")]
    private int $createdAt;

    #[
        ORM\ManyToMany(targetEntity: "Grape", inversedBy: "wines"),
        ORM\JoinTable(name: "wine_grape"),
        ORM\JoinColumn(name: "wine_id", referencedColumnName: "id", onDelete: "CASCADE"),
        ORM\InverseJoinColumn(name: "grape_id", referencedColumnName: "id", onDelete: "CASCADE"),
        Assert\Count(min: 1, minMessage: "The wine must have at least one grape."),
    ]
    private Collection $grapes;

    #[
        ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy: "wines"),
        ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false),
    ]
    private User $user;

    #[
        ORM\ManyToOne(targetEntity: "TasteProfile", inversedBy: "wines"),
        ORM\JoinColumn(name: "taste_profile_id", referencedColumnName: "id", nullable: true),
    ]
    private ?TasteProfile $tasteProfile = null;

    #[
        ORM\ManyToOne(targetEntity: "Country", inversedBy: "wines"),
        ORM\JoinColumn(name: "country_id", referencedColumnName: "id", nullable: false),
    ]
    private Country $country;

    #[
        ORM\ManyToOne(targetEntity: "Region", inversedBy: "wines"),
        ORM\JoinColumn(name: "region_id", referencedColumnName: "id", nullable: true),
    ]
    private ?Region $region = null;

    #[
        ORM\ManyToOne(targetEntity: "Subregion", inversedBy: "wines"),
        ORM\JoinColumn(name: "subregion_id", referencedColumnName: "id", nullable: true),
    ]
    private ?Subregion $subregion = null;

    #[Pure] public function __construct()
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
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
        $this->description = strip_tags($description);
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): void
    {
        $this->review = strip_tags($review);
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * The label getter and setter have to exist for the wine form to work,
     * but the value of getLabel is never used because a html input of type file cannot be prefilled.
     * The setter only sets the labelExtension, because it does not save the image.
     * The function moveLabel saves the image.
     */
    public function getLabel(): void
    {
    }
    public function setLabel(?UploadedFile $label): void
    {
        if (!is_null($label)) {
            $this->setLabelExtension($label->getClientOriginalExtension());
        }
    }

    public function moveLabel(?UploadedFile $label, string $appEnv, string $projectDir)
    {
        if (!is_null($label)) {
            $id = (string) $this->getId();
            if (!str_starts_with($label->getMimeType(), 'image/')) {
                throw new ValidatorException('The file is not an image.');
            }
            $extraPath = '';
            if ($appEnv === 'test') {
                $extraPath = 'test/';
            }
            $label->move(
                $projectDir . '/uploads/wine/labels/' . $extraPath,
                $id . '.' . $label->getClientOriginalExtension()
            );
        }
    }

    #[Pure] public function getLabelPath(string $appEnv): string
    {
        $idString = (string) $this->getId();
        $extraPath = '';
        if ($appEnv === 'test') {
            $extraPath = 'test/';
        }

        return 'uploads/wine/labels/' . $extraPath . $idString . '.' . $this->getLabelExtension();
    }

    public function unlinkLabel(string $appEnv, string $projectDir)
    {
        unlink($projectDir . '/' . $this->getLabelPath($appEnv));
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): void
    {
        $this->region = $region;
    }

    public function getSubregion(): ?Subregion
    {
        return $this->subregion;
    }

    public function setSubregion(?Subregion $subregion): void
    {
        $this->subregion = $subregion;
    }
}
