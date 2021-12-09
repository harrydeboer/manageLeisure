<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

class AuthWebTestCase extends WebTestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->setName('John');
        $this->user->setEmail('john@secret.com');

        $this->user = $this->getContainer()->get(UserRepositoryInterface::class)->create($this->user, 'secret');

        $this->client->loginUser($this->user);
    }
}