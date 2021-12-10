<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

class UserFactory
{
    public function __construct(
       private UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(): User
    {
        $user = new User();
        $user->setName(uniqid('userName'));
        $user->setEmail(uniqid('userEmail'));

        $this->userRepository->create($user, 'secret');

        return $user;
    }
}
