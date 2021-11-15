<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Repository;

use App\Tests\AuthRepositoryTestCase;
use App\WineBundle\Entity\Category;
use App\WineBundle\Repository\CategoryRepositoryInterface;
use Error;

class CategoryRepositoryTest extends AuthRepositoryTestCase
{
    public function testCreateUpdateDelete()
    {
        $category = new Category();
        $category->setName('fresh');
        $category->setUser($this->user);

        $categoryRepository = static::getContainer()->get(CategoryRepositoryInterface::class);
        $categoryRepository->create($category);

        $this->assertSame($category, $categoryRepository->find($category->getId()));

        $category->setName('test2');

        $categoryRepository->update();

        $this->assertSame('test2', $categoryRepository->find($category->getId())->getName());

        $categoryRepository->delete($category);

        $this->expectException(Error::class);

        $categoryRepository->find($category->getId());
    }
}
