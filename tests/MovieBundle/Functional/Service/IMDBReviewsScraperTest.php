<?php

declare(strict_types=1);

namespace App\Tests\MovieBundle\Functional\Service;

use App\MovieBundle\Service\IMDBReviewsScraper;
use App\Tests\Functional\KernelTestCase;

class IMDBReviewsScraperTest extends KernelTestCase
{
    public function testGetRating()
    {
        $result = IMDBReviewsScraper::getRating('tt0068646');
        $this->assertIsFloat( $result);
    }
}
