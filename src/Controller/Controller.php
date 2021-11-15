<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class Controller extends AbstractController
{
    /**
     * @return User
     */
    protected function getCurrentUser(): UserInterface
    {
        return $this->getUser();
    }

    protected function checkUser(User $user): void
    {
        if ($user !== $this->getCurrentUser()) {
            throw new AuthenticationException('This user cannot edit this id');
        }
    }
}
