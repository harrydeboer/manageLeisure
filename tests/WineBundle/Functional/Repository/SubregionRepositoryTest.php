<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\SubregionFactory;
use App\WineBundle\Repository\SubregionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubregionRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $subregion = static::getContainer()->get(SubregionFactory::class)->create();

        $subregionRepository = static::getContainer()->get(SubregionRepositoryInterface::class);

        $this->assertSame($subregion, $subregionRepository->find($subregion->getId()));

        $updatedName = 'subregionUpdate';
        $subregion->setName($updatedName);

        $subregionRepository->update();

        $this->assertSame($updatedName, $subregionRepository->find($subregion->getId())->getName());

        $id = $subregion->getId();
        $subregionRepository->delete($subregion);

        $this->expectException(NotFoundHttpException::class);

        $subregionRepository->get($id);
    }
}
