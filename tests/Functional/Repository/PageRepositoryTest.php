<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Factory\PageFactory;
use App\Repository\PageRepositoryInterface;
use App\Tests\Functional\KernelTestCase;

class PageRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $page = static::getContainer()->get(PageFactory::class)->create();

        $pageRepository = static::getContainer()->get(PageRepositoryInterface::class);

        $this->assertSame($page, $pageRepository->find($page->getId()));

        $updatedTitle = 'Test2';
        $page->setTitle($updatedTitle);
        $page->setSlug(strtolower($updatedTitle));

        $pageRepository->update();

        $id = $page->getId();
        $pageRepository->delete($page);

        $this->assertNull($pageRepository->find($id));
    }
}
