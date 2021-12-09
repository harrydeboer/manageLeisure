<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');
    }
}
