<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\Entity\User;
use App\Pagination\Paginator;
use App\WineBundle\Entity\Wine;

interface WineRepositoryInterface
{
    public function getFromUser(int $id, int $userId): Wine;

    public function create(Wine $wine): Wine;

    public function update(): void;

    public function delete(Wine $wine): void;

    public function findAllOfUser(User $user): array;

    public function findBySortAndFilter(
        User $user,
        int $page,
        array $formData = null,
    ): Paginator|array;
}
