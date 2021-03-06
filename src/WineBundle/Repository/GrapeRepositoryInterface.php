<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Grape;

interface GrapeRepositoryInterface
{
    public function get(int $id): Grape;

    public function create(Grape $grape): Grape;

    public function update(): void;

    public function delete(Grape $grape): void;

    public function findAllOrderedByName(): array;
}
