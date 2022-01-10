<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

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
        $country = $this->countryFactory->create();

        $region = new Region();
        $region->setName($this->generateRandomString());
        $region->setCountry($country);

        $this->setParams($params, $region);

        return $this->regionRepository->create($region);
    }
}
