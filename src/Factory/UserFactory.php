<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

class UserFactory extends AbstractFactory
{
    public function __construct(
       private UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(array $params = []): User
    {
        $user = new User();
        $user->setName(uniqid('userName'));
        $user->setEmail(uniqid('userEmail'));
        $user->addRoles(['ROLE_USER_VERIFIED']);
        $user->setIsVerified(true);

        $this->setParams($params, $user);

        return $this->userRepository->create($user, 'secret');
    }
}
