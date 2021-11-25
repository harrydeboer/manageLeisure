<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Repository;

use App\Tests\Feature\AuthRepositoryTestCase;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\WineRepositoryInterface;
use Error;

class WineRepositoryTest extends AuthRepositoryTestCase
{
    private Wine $wine;
    private WineRepositoryInterface $wineRepository;

    public function setUp(): void
    {
        parent::setUp();

        $wine = new Wine();
        $wine->setUser($this->user);
        $wine->setName('test');
        $wine->setCreatedAt(time());
        $wine->setPrice(10);
        $wine->setRating(8);
        $wine->setYear(2000);
        $wine->setImageExtension('png');

        $this->wine = $wine;

        $this->wineRepository = static::getContainer()->get(WineRepositoryInterface::class);
    }

    public function testCreateUpdateDelete()
    {
        $this->wineRepository->create($this->wine);

        $this->assertSame($this->wine, $this->wineRepository->find($this->wine->getId()));

        $this->wine->setName('test2');

        $this->wineRepository->update();

        $this->assertSame('test2', $this->wineRepository->find($this->wine->getId())->getName());

        $this->wineRepository->delete($this->wine);

        $this->expectException(Error::class);

        $this->wineRepository->find($this->wine->getId());
    }

    public function testFindLatest()
    {
        $this->wineRepository->create($this->wine);

        $wines = $this->wineRepository->findLatest($this->user, 1)->getResults();

        $this->assertSame($this->wine, $wines->current());
    }
}
