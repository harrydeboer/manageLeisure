<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface PageRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function getFromUser(int $id, int $userId): Page;

    public function getByTitle(string $title): Page;

    public function create(Page $page): Page;

    public function update(): void;

    public function delete(Page $page): void;
}