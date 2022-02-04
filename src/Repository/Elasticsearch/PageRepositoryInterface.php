<?php

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;

interface PageRepositoryInterface
{
    public function index(\App\Entity\Page $page): void;

    public function getByTitle(string $title): Page;

    public function getBySlug(string $slug): Page;
}
