<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;

class StatsControllerTest extends AuthWebTestCase
{
    public function testView(): void
    {
        $this->client->request('GET', '/wine/stats');

        $this->assertResponseIsSuccessful();
    }
}
