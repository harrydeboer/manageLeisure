<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\CountryFactory;
use App\WineBundle\Repository\CountryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountryRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete(): void
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
