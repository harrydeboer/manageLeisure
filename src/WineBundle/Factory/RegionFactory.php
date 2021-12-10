<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\CountryFactory;
use App\WineBundle\Entity\Region;
use App\WineBundle\Repository\RegionRepositoryInterface;

class RegionFactory
{
    public function __construct(
        private CountryFactory $countryFactory,
        private RegionRepositoryInterface $regionRepository,
    ) {
    }

    public function create(User $user=null): Region
    {
        $country = $this->countryFactory->create($user);

        $region = new Region();
        $region->setName('jan');
        $region->setCountry($country);
        $region->setUser($country->getUser());

        $this->regionRepository->create($region);

        return $region;
    }
}
