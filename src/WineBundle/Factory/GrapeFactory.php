<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;

class GrapeFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private GrapeRepositoryInterface $grapeRepository,
    ) {
    }

    public function create(User $user=null): Grape
    {
        $grape = new Grape();
        if (is_null($user)) {
            $grape->setUser($this->userFactory->create());
        } else {
            $grape->setUser($user);
        }
        $grape->setName(uniqid('grape'));
        $grape->setType(array_rand(['red' => 0, 'white' => 1]));;

        $this->grapeRepository->create($grape);

        return $grape;
    }
}
