<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\AbstractFactory;
use App\Factory\UserFactory;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;

class GrapeFactory extends AbstractFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private GrapeRepositoryInterface $grapeRepository,
    ) {
    }

    public function create(array $params = []): Grape
    {
        $grape = new Grape();
        $grape->setUser($this->userFactory->create());
        $grape->setName(uniqid('grape'));
        $grape->setType(array_rand(['red' => 0, 'white' => 1]));

        $this->setParams($params, $grape);

        return $this->grapeRepository->create($grape);
    }
}
