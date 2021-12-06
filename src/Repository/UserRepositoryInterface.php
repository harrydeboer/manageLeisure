<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface UserRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(User $user, string $plainPassword): User;
}
