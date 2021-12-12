<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

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
        $paramsParent = [];
        if (isset($params['user'])) {
            $paramsParent['user'] = $params['user'];
        }
        if (isset($params['country'])) {
            $paramsParent['country'] = $params['country'];
        }

        $region = $this->regionFactory->create($paramsParent);

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
