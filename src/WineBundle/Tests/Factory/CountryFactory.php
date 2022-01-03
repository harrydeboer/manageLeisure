<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Factory;

use App\Tests\Factory\AbstractFactory;
use App\Tests\Factory\UserFactory;
use App\WineBundle\Entity\Country;
use App\WineBundle\Repository\CountryRepositoryInterface;

class CountryFactory extends AbstractFactory
{
    public function __construct(
       private UserFactory $userFactory,
       protected CountryRepositoryInterface $countryRepository,
    ) {
    }

    public function create(array $params = []): Country
    {
        $country = new Country();
        $country->setName('jan');
        $country->setUser($this->userFactory->create());

        $this->setParams($params, $country);

        return $this->countryRepository->create($country);
    }
}
