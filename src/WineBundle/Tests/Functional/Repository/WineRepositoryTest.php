<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\WineFactory;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class WineRepositoryTest extends KernelTestCase
{
    private WineRepositoryInterface $wineRepository;
    private WineFactory $wineFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->wineRepository = static::getContainer()->get(WineRepositoryInterface::class);

        $this->wineFactory = static::getContainer()->get(WineFactory::class);
    }

    /**
     * @throws Exception
     */
    public function testCreateUpdateDelete()
    {
        $wine = $this->wineFactory->create();

        $this->assertSame($wine, $this->wineRepository->find($wine->getId()));

        $wine->setName('test2');

        $this->wineRepository->update();

        $this->assertSame('test2', $this->wineRepository->find($wine->getId())->getName());

        $id = $wine->getId();
        $this->wineRepository->delete($wine);

        $this->expectException(NotFoundHttpException::class);

        $this->wineRepository->getFromUser($id, $wine->getUser()->getId());
    }

    /**
     * @throws Exception
     */
    public function testFindBySortAndFilter()
    {
        $wine = $this->wineFactory->create();

        $this->assertCount(1, $this->wineRepository->findBySortAndFilter($wine->getUser(), 1)->getResults());
    }
}
