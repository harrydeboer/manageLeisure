<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Factory;

use App\Tests\Factory\AbstractFactory;
use App\Tests\Factory\UserFactory;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;

class TasteProfileFactory extends AbstractFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private TasteProfileRepositoryInterface $tasteProfileRepository,
    ) {
    }

    public function create(array $params = []): TasteProfile
    {
        $tasteProfile = new TasteProfile();
        $tasteProfile->setUser($this->userFactory->create());
        $tasteProfile->setName(uniqid('tasteProfile'));
        $tasteProfile->setSecondName(uniqid('tasteProfileSecond'));
        $tasteProfile->setDescription(uniqid('tasteProfileDescription'));

        $this->setParams($params, $tasteProfile);

        return $this->tasteProfileRepository->create($tasteProfile);
    }
}
