<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;

interface CountryRepositoryInterface
{
    public function get(int $id): Country;

    public function create(Country $country): Country;

    public function update(): void;

    public function delete(Country $country): void;
}
