<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Region;

interface RegionRepositoryInterface
{
    public function getFromUser(int $id, int $userId): Region;

    public function create(Region $region): Region;

    public function update(): void;

    public function delete(Region $region): void;
}
