<?php

declare(strict_types=1);

namespace App\MovieBundle\Tests\Functional\Service;

use App\MovieBundle\Service\IMDBIdRetriever;
use App\Tests\Functional\WebTestCase;

class IMDBIdRetrieverTest extends WebTestCase
{
    public function testGetResponseObject()
    {
        $result = IMDBIdRetriever::getResponseObject('The Godfather', $this->getContainer()->getParameter('omdb_api_key'));
        $this->assertEquals($result->Response, 'True');
        $this->assertObjectHasAttribute('Search', $result);

        $result = IMDBIdRetriever::getResponseObject('The Godfather',
            $this->getContainer()->getParameter('omdb_api_key'), 1972);
        $this->assertEquals($result->Title, 'The Godfather');
        $this->assertEquals($result->Year, '1972');
    }
}
