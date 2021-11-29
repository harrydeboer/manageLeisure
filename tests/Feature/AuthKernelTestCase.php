<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthKernelTestCase extends KernelTestCase
{
    use MigrationsTrait;

    protected User $user;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->migrateDb();

        $this->user = $this->user = new User();
        $this->user->setUsername('john');
        $this->user->setEmail('john@secret.com');

        $this->user = $this->getContainer()->get(UserRepositoryInterface::class)->create($this->user, 'secret');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dropAndCreateDb();
    }
}