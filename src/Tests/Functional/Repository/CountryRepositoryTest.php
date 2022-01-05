<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Tests\Factory\CountryFactory;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountryRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $country = static::getContainer()->get(CountryFactory::class)->create();

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);

        $this->assertSame($country, $countryRepository->find($country->getId()));

        $updatedName = 'Italy';
        $country->setName($updatedName);

        $countryRepository->update();

        $this->assertSame($updatedName, $countryRepository->find($country->getId())->getName());

        $id = $country->getId();
        $countryRepository->delete($country);

        $this->expectException(NotFoundHttpException::class);

        $countryRepository->get($id);
    }
}
