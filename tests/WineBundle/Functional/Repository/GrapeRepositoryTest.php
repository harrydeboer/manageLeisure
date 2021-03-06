<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\GrapeFactory;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GrapeRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $grape = static::getContainer()->get(GrapeFactory::class)->create();

        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        $this->assertSame($grape, $grapeRepository->find($grape->getId()));

        $updatedName = 'grapeUpdate';
        $grape->setName($updatedName);

        $grapeRepository->update();

        $this->assertCount(1, $grapeRepository->findAllOrderedByName());

        $this->assertSame($updatedName, $grapeRepository->find($grape->getId())->getName());

        $id = $grape->getId();
        $grapeRepository->delete($grape);

        $this->expectException(NotFoundHttpException::class);

        $grapeRepository->get($id);
    }
}
