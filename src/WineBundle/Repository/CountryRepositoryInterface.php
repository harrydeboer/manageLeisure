<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Country;
use App\Entity\User;

interface CountryRepositoryInterface
{
    public function getFromUser(int $id, int $userId): Country;

    public function create(Country $country): Country;

    public function update(): void;

    public function delete(Country $country): void;

    public function findOrderedByName(User $user): array;
}
