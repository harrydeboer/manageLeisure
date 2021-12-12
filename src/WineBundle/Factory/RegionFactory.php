<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\AbstractFactory;
use App\Factory\CountryFactory;
use App\WineBundle\Entity\Region;
use App\WineBundle\Repository\RegionRepositoryInterface;

class RegionFactory extends AbstractFactory
{
    public function __construct(
        private CountryFactory $countryFactory,
        private RegionRepositoryInterface $regionRepository,
    ) {
    }

    public function create(array $params = []): Region
    {
        if (isset($params['user'])) {
            $country = $this->countryFactory->create(['user' => $params['user']]);
        } else {
            $country = $this->countryFactory->create($params);
        }

        $region = new Region();
        $region->setName('jan');
        $region->setCountry($country);
        $region->setUser($country->getUser());

        $this->setParams($params, $region);

        return $this->regionRepository->create($region);
    }
}
