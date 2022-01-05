<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Region;
use App\Repository\RegionRepositoryInterface;

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
        $region->setName('jan');
        $region->setCountry($country);

        $this->setParams($params, $region);

        return $this->regionRepository->create($region);
    }
}
