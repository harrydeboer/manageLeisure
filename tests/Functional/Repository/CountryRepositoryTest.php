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
        $country = $this->createCountry($this->user);

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);

        $this->assertSame($country, $countryRepository->find($country->getId()));

        $country->setName('Italy');

        $countryRepository->update();

        $this->assertSame('Italy', $countryRepository->find($country->getId())->getName());

        $id = $country->getId();
        $countryRepository->delete($country);

        $this->expectException(NotFoundHttpException::class);

        $countryRepository->getFromUser($id, $this->user->getId());
    }
}
