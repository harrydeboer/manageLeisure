<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;

class TasteProfileFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private TasteProfileRepositoryInterface $tasteProfileRepository,
    ) {
    }

    public function create(User $user=null): TasteProfile
    {
        $tasteProfile = new TasteProfile();
        if (is_null($user)) {
            $tasteProfile->setUser($this->userFactory->create());
        } else {
            $tasteProfile->setUser($user);
        }
        $tasteProfile->setName(uniqid('tasteProfile'));
        $tasteProfile->setSecondName(uniqid('tasteProfileSecond'));
        $tasteProfile->setDescription(uniqid('tasteProfileDescription'));

        $this->tasteProfileRepository->create($tasteProfile);

        return $tasteProfile;
    }
}
