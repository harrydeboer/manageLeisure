<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(User $user, string $plainPassword): User;

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newPassword): void;
}
