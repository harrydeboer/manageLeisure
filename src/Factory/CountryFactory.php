<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Country;
use App\Entity\User;
use App\Repository\CountryRepositoryInterface;

class CountryFactory
{
    public function __construct(
       private UserFactory $userFactory,
       private CountryRepositoryInterface $countryRepository,
    ) {
    }

    public function create(User $user=null): Country
    {
        $country = new Country();
        $country->setName('jan');
        if (is_null($user)) {
            $country->setUser($this->userFactory->create());
        } else {
            $country->setUser($user);
        }

        $this->countryRepository->create($country);

        return $country;
    }
}
