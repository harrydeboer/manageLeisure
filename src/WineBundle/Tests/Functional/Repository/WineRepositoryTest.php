<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WineRepositoryTest extends AuthKernelTestCase
{
    private WineRepositoryInterface $wineRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->wineRepository = static::getContainer()->get(WineRepositoryInterface::class);
    }

    public function testCreateUpdateDelete()
    {
        $wine = $this->createWine($this->user);

        $this->assertSame($wine, $this->wineRepository->find($wine->getId()));

        $wine->setName('test2');

        $this->wineRepository->update();

        $this->assertSame('test2', $this->wineRepository->find($wine->getId())->getName());

        $id = $wine->getId();
        $this->wineRepository->delete($wine);

        $this->expectException(NotFoundHttpException::class);

        $this->wineRepository->find($id);
    }

    public function findBySortAndFilter()
    {
        $this->assertCount(1, $this->wineRepository->findBySortAndFilter($this->user, 1));
    }
}
