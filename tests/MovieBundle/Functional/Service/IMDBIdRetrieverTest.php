<?php

declare(strict_types=1);

namespace App\Tests\MovieBundle\Functional\Service;

use App\MovieBundle\Service\IMDBIdRetriever;
use App\Tests\Functional\KernelTestCase;

class IMDBIdRetrieverTest extends KernelTestCase
{
    public function testGetResponseObject()
    {
        $title = 'The Godfather';
        $year = 1972;
        $result = IMDBIdRetriever::getResponseObject($title, $this->getContainer()->getParameter('omdb_api_key'));
        $this->assertEquals( 'True', $result->Response);
        $this->assertObjectHasAttribute('Search', $result);

        $result = IMDBIdRetriever::getResponseObject($title,
            $this->getContainer()->getParameter('omdb_api_key'), $year);
        $this->assertEquals( 'The Godfather', $result->Title);
        $this->assertEquals( (string) $year, $result->Year);
    }
}
