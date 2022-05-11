<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Unit\Service;

use App\WineBundle\Entity\Country;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Service\StatsCalculator;
use PHPUnit\Framework\TestCase;

class StatsCalculatorTest extends TestCase
{
    public function testAverage()
    {
        $wineMock = $this->createMock(Wine::class);
        $wineMock->method('getPrice')->willReturn(1.0);
        $wineMock->method('getRating')->willReturn(1.0);
        $wineMock->method('getPercentage')->willReturn(1.0);
        $average = StatsCalculator::average([$wineMock]);
        $this->assertEquals(1.0, $average['price']);
    }

    public function testPieChart()
    {
        $wineMock = $this->createMock(Wine::class);
        $countryMock = $this->createMock(Country::class);
        $wineMock->method('getCountry')->willReturn($countryMock);
        $countryMock->method('getName')->willReturn('France');
        $country = json_decode(StatsCalculator::pieChart([$wineMock]), true);
        $this->assertEquals(1, $country['France']);
    }
}
