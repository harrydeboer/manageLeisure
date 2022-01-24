<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Factory\AbstractFactory;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;

class GrapeFactory extends AbstractFactory
{
    public function __construct(
        private GrapeRepositoryInterface $grapeRepository,
    ) {
    }

    public function create(array $params = []): Grape
    {
        $grape = new Grape();
        $grape->setName(uniqid('grape'));
        $types = Grape::TYPES;
        $randomTypeKey = array_rand($types);
        $grape->setType($types[$randomTypeKey]);

        $this->setParams($params, $grape);

        return $this->grapeRepository->create($grape);
    }
}
