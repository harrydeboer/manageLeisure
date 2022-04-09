<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Repository;

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
    public function testCreateUpdateDelete(): void
    {
        $wine = $this->wineFactory->create();

        $this->assertSame($wine, $this->wineRepository->find($wine->getId()));

        $updatedName = 'test2';
        $wine->setName($updatedName);

        $this->wineRepository->update();

        $this->assertSame($updatedName, $this->wineRepository->find($wine->getId())->getName());

        $id = $wine->getId();
        $this->wineRepository->delete($wine);

        $this->expectException(NotFoundHttpException::class);

        $this->wineRepository->getFromUser($id, $wine->getUser()->getId());
    }

    /**
     * @throws Exception
     */
    public function testFindAllOfUser(): void
    {
        $wine = $this->wineFactory->create();

        $this->assertCount(1, $this->wineRepository->findAllOfUser($wine->getUser()));
    }

    /**
     * @throws Exception
     */
    public function testFindBySortAndFilter(): void
    {
        $wine = $this->wineFactory->create();

        $this->assertCount(1, $this->wineRepository->findBySortAndFilter($wine->getUser(), 1)->getResults());
    }
}
