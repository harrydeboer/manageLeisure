<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\WineRepositoryInterface;

class WineFactory
{
    public function __construct(
        private RegionFactory $regionFactory,
        private WineRepositoryInterface $wineRepository,
    ) {
    }

    public function create(User $user=null): Wine
    {
        $region = $this->regionFactory->create($user);

        $wine = new Wine();
        $wine->setUser($region->getUser());
        $wine->setName(uniqid('wine'));
        $wine->setRegion($region);
        $wine->setCountry($region->getCountry());
        $wine->setLabelExtension('png');
        $wine->setYear(random_int(1000, 9999));
        $wine->setRating(random_int(1, 10));
        $wine->setPrice(random_int(1, 100));
        $wine->setCreatedAt(time());

        $this->wineRepository->create($wine);

        return $wine;
    }
}
