<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Factory\AbstractFactory;
use App\WineBundle\Entity\Subregion;
use App\WineBundle\Repository\SubregionRepositoryInterface;

class SubregionFactory extends AbstractFactory
{
    public function __construct(
        private RegionFactory $regionFactory,
        private SubregionRepositoryInterface $subregionRepository,
    ) {
    }

    public function create(array $params = []): Subregion
    {
        $region = $this->regionFactory->create();

        $subregion = new Subregion();
        $subregion->setName($this->generateRandomString());
        $subregion->setRegion($region);

        $this->setParams($params, $subregion);

        return $this->subregionRepository->create($subregion);
    }
}
