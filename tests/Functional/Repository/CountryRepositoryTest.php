<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\Country;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthKernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountryRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $country = new Country();
        $country->setName('France');
        $country->setUser($this->user);

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);
        $countryRepository->create($country);

        $this->assertSame($country, $countryRepository->find($country->getId()));

        $country->setName('Italy');

        $countryRepository->update();

        $this->assertSame('Italy', $countryRepository->find($country->getId())->getName());

        $id = $country->getId();
        $countryRepository->delete($country);

        $this->expectException(NotFoundHttpException::class);

        $countryRepository->find($id);
    }
}
