<?php

declare(strict_types=1);

namespace App\AdminBundle\Entity;

use App\AdminBundle\Repository\MailUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MailUserRepository::class)]
#[ORM\Table(name: "mail_user")]
#[ORM\UniqueConstraint(fields: ["email"])]
#[UniqueEntity(fields: ["email"], message: "There is already a mail user with this email.")]
class MailUser
{
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\Column(type: "string", length: 180)]
    private string $domain;

    #[ORM\Column(type: "string", length: 180)]
    private string $password;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 180, nullable: true)]
    private ?string $forward = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = strip_tags($domain);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * The new password getter and setter are needed for the form, but the values are not used.
     */
    public function getNewPassword(): void
    {
    }
    public function setNewPassword(?string $newPassword): void
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getForward(): ?string
    {
        return $this->forward;
    }

    public function setForward(?string $forward): void
    {
        $this->forward = $forward;
    }
}
