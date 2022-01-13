<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Subregion;

interface SubregionRepositoryInterface
{
    public function get(int $id): Subregion;

    public function create(Subregion $subregion): Subregion;

    public function update(): void;

    public function delete(Subregion $subregion): void;

    public function findAllOrderedByName(int $regionId): array;
}
