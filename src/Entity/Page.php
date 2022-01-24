<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[
    ORM\Entity(repositoryClass: PageRepository::class),
    ORM\Table(name: "page"),
    ORM\UniqueConstraint(name: "title_unique", fields: ["title"]),
    ORM\UniqueConstraint(name: "slug_unique", fields: ["slug"]),
    UniqueEntity(fields: ["title"], message: "There is already a page with this title."),
    UniqueEntity(fields: ["slug"], message: "There is already a page with this slug."),
]
class Page
{
    #[
        ORM\Id,
        ORM\Column(type: "integer"),
        ORM\GeneratedValue(strategy: "IDENTITY"),
    ]
    private int $id;

    #[
        ORM\Column(type: "string"),
        Assert\NotBlank,
    ]
    private string $title;

    #[
        ORM\Column(type: "string"),
        Assert\NotBlank,
    ]
    private string $slug;

    #[
        ORM\Column(type: "string", nullable: true),
        Assert\Length(max: 255),
    ]
    private ?string $summary = null;

    #[
        ORM\Column(type: "text"),
        Assert\Length(min: 10),
    ]
    private string $content;

    #[ORM\Column(type: "integer")]
    private int $publishedAt;

    #[
        ORM\ManyToOne(targetEntity: "User", inversedBy: "pages"),
        ORM\JoinColumn(name: "author_id", referencedColumnName: "id", nullable: false),
    ]
    private User $author;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = strtolower($slug);
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPublishedAt(): int
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(int $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }
}
