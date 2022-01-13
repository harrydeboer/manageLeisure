<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Factory\SubregionFactory;

class SubregionControllerTest extends AuthWebTestCase
{
    public function testGetRegions(): void
    {
        $subregion = $this->getContainer()->get(SubregionFactory::class)->create();
        $this->client->xmlHttpRequest('GET', '/wine/get-subregions/' . $subregion->getId());

        $this->assertResponseIsSuccessful();
    }
}
