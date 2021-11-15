<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Category;

interface CategoryRepositoryInterface
{
    public function create(Category $category): void;

    public function update(): void;

    public function delete(Category $category): void;
}
