<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Entity\Country;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Entity\Region;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegionRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
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

        $this->assertSame($region, $regionRepository->find($region->getId()));

        $region->setName('test2');

        $regionRepository->update();

        $this->assertSame('test2', $regionRepository->find($region->getId())->getName());

        $id = $region->getId();
        $regionRepository->delete($region);

        $this->expectException(NotFoundHttpException::class);

        $regionRepository->find($id);
    }
}
