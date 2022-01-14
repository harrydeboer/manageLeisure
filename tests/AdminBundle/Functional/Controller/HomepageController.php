<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Functional\Controller;

use App\Repository\UserRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;

class HomepageController extends AuthWebTestCase
{
    public function testHomepage(): void
    {
        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);
        $this->user->setRoles(['ROLE_ADMIN']);
        $userRepository->update();
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/admin/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Pages');
    }
}
