<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\TasteProfile;

interface TasteProfileRepositoryInterface
{
    public function create(TasteProfile $tasteProfile): void;

    public function update(): void;

    public function delete(TasteProfile $tasteProfile): void;
}
