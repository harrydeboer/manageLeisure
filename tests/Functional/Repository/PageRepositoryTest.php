<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Factory\PageFactory;
use App\Repository\PageRepositoryInterface;
use App\Tests\Functional\KernelTestCase;

class PageRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $page = static::getContainer()->get(PageFactory::class)->create();

        $pageRepository = static::getContainer()->get(PageRepositoryInterface::class);

        $this->assertSame($page, $pageRepository->find($page->getId()));

        $updatedTitle = 'test2';
        $page->setTitle($updatedTitle);

        $pageRepository->update();

        $this->assertSame($updatedTitle, $pageRepository->find($page->getId())->getTitle());

        $id = $page->getId();
        $pageRepository->delete($page);

        $this->assertNull($pageRepository->find($id));
    }
}
