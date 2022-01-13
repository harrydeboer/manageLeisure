<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Factory\RegionFactory;

class RegionControllerTest extends AuthWebTestCase
{
    public function testGetRegions(): void
    {
        $region = $this->getContainer()->get(RegionFactory::class)->create();
        $this->client->xmlHttpRequest('GET', '/wine/get-regions/' . $region->getId());

        $this->assertResponseIsSuccessful();
    }
}
