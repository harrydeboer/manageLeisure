<?php

declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use App\Tests\Feature\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');
    }
}
