<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\Factory\RegionFactory;
use App\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegionRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $region = static::getContainer()->get(RegionFactory::class)->create();

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);

        $this->assertSame($region, $regionRepository->find($region->getId()));

        $updatedName = 'regionUpdate';
        $region->setName($updatedName);

        $regionRepository->update();

        $this->assertSame($updatedName, $regionRepository->find($region->getId())->getName());

        $id = $region->getId();
        $regionRepository->delete($region);

        $this->expectException(NotFoundHttpException::class);

        $regionRepository->get($id);
    }
}
