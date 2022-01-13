<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Country;

interface CountryRepositoryInterface
{
    public function get(int $id): Country;

    public function create(Country $country): Country;

    public function update(): void;

    public function delete(Country $country): void;
}
