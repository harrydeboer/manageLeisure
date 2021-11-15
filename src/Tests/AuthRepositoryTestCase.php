<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthRepositoryTestCase extends KernelTestCase
{
    use MigrationsTrait;

    protected User $user;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->migrate();

        $this->user = $this->user = new User();
        $this->user->setUsername('john');
        $this->user->setEmail('john@secret.com');

        $this->user = $this->getContainer()->get(UserRepositoryInterface::class)->create($this->user, 'secret');
    }

    protected function tearDown(): void
    {
        $this->drop();

        parent::tearDown();
    }
}
