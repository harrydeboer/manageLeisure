<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class AuthControllerTestCase extends WebTestCase
{
    use MigrationsTrait;

    protected AbstractBrowser $client;
    protected User $user;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->migrate();

        $this->user = new User();
        $this->user->setUsername('john');
        $this->user->setEmail('john@secret.com');

        $this->user = $this->getContainer()->get(UserRepositoryInterface::class)->create($this->user, 'secret');

        $this->client->loginUser($this->user);
    }

    public function tearDown(): void
    {
        $this->drop();
        parent::tearDown();
    }
}
