<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Entity\Country;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Entity\Region;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\RegionRepositoryInterface;
use App\WineBundle\Repository\WineRepositoryInterface;
use Error;

class WineRepositoryTest extends AuthKernelTestCase
{
    private Wine $wine;
    private WineRepositoryInterface $wineRepository;

    public function setUp(): void
    {
        parent::setUp();

        $country = new Country();
        $country->setUser($this->user);
        $country->setName('France');

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);
        $countryRepository->create($country);

        $region = new Region();
        $region->setUser($this->user);
        $region->setCountry($country);
        $region->setName('Bordeaux');

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);
        $regionRepository->create($region);

        $wine = new Wine();
        $wine->setUser($this->user);
        $wine->setName('test');
        $wine->setCreatedAt(time());
        $wine->setPrice(10);
        $wine->setRating(8);
        $wine->setYear(2000);
        $wine->setLabelExtension('png');
        $wine->setCountry($country);
        $wine->setRegion($region);

        $this->wine = $wine;

        $this->wineRepository = static::getContainer()->get(WineRepositoryInterface::class);
    }

    public function testCreateUpdateDelete()
    {
        $this->wineRepository->create($this->wine);

        $this->assertSame($this->wine, $this->wineRepository->find($this->wine->getId()));

        $this->wine->setName('test2');

        $this->wineRepository->update();

        $this->assertSame('test2', $this->wineRepository->find($this->wine->getId())->getName());

        $this->wineRepository->delete($this->wine);

        $this->expectException(Error::class);

        $this->wineRepository->find($this->wine->getId());
    }

    public function findBySortAndFilter()
    {
        $this->assertEquals(1, count($this->wineRepository->findBySortAndFilter($this->user, 1)));
    }
}
