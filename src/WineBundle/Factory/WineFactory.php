<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\AbstractFactory;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\WineRepositoryInterface;

class WineFactory extends AbstractFactory
{
    public function __construct(
        private RegionFactory $regionFactory,
        private WineRepositoryInterface $wineRepository,
    ) {
    }

    public function create(array $params = []): Wine
    {
        if (isset($params['user'])) {
            $region = $this->regionFactory->create(['user' => $params['user']]);
        } else {
            $region = $this->regionFactory->create($params);
        }

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

        $this->setParams($params, $wine);

        return $this->wineRepository->create($wine);
    }
}
