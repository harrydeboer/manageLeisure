<?php

declare(strict_types=1);

namespace App\Tests\Feature\Repository;

use App\Entity\Country;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Feature\AuthKernelTestCase;
use Error;

class CountryRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $country = new Country();
        $country->setName('France');
        $country->setCode('FR');
        $country->setUser($this->user);

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);
        $countryRepository->create($country);

        $this->assertSame($country, $countryRepository->find($country->getId()));

        $country->setName('Italy');
        $country->setCode('IT');

        $countryRepository->update();

        $this->assertSame('Italy', $countryRepository->find($country->getId())->getName());

        $id = $country->getId();
        $countryRepository->delete($country);

        $this->assertNull($countryRepository->find($id));
    }
}
