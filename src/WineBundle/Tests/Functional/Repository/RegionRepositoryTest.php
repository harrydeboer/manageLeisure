<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\RegionFactory;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegionRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $region = static::getContainer()->get(RegionFactory::class)->create();

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);

        $this->assertSame($region, $regionRepository->find($region->getId()));

        $region->setName('regionUpdate');

        $regionRepository->update();

        $this->assertSame('regionUpdate', $regionRepository->find($region->getId())->getName());

        $id = $region->getId();
        $regionRepository->delete($region);

        $this->expectException(NotFoundHttpException::class);

        $regionRepository->getFromUser($id, $region->getUser()->getId());
    }
}
