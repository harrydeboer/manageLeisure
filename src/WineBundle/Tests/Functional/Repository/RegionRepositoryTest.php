<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegionRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $region = $this->createRegion($this->user);

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);

        $this->assertSame($region, $regionRepository->find($region->getId()));

        $region->setName('test2');

        $regionRepository->update();

        $this->assertSame('test2', $regionRepository->find($region->getId())->getName());

        $id = $region->getId();
        $regionRepository->delete($region);

        $this->expectException(NotFoundHttpException::class);

        $regionRepository->getFromUser($id, $this->user->getId());
    }
}
