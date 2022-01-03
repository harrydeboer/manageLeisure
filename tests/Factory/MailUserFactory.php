<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\MailUser;
use App\Repository\MailUserRepositoryInterface;

class MailUserFactory extends AbstractFactory
{
    public function __construct(
       private MailUserRepositoryInterface $mailUserRepository,
    ) {
    }

    public function create(array $params = []): MailUser
    {
        $user = new MailUser();
        $user->setDomain('manageleisure.com');
        $user->setPassword('secret');
        $user->setEmail(uniqid('userEmail'));
        $user->setForward(uniqid('userEmail'));

        $this->setParams($params, $user);

        return $this->mailUserRepository->create($user);
    }
}
