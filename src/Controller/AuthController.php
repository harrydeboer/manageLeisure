<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    /**
     * @return User
     */
    protected function getUser(): UserInterface
    {
        return parent::getUser();
    }
}
