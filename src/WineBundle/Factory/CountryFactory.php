<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Factory\AbstractFactory;
use App\WineBundle\Entity\Country;
use App\WineBundle\Repository\CountryRepositoryInterface;

class CountryFactory extends AbstractFactory
{
    public function __construct(
       protected CountryRepositoryInterface $countryRepository,
    ) {
    }

    public function create(array $params = []): Country
    {
        $country = new Country();
        $country->setName($this->generateRandomString());

        $this->setParams($params, $country);

        return $this->countryRepository->create($country);
    }
}
