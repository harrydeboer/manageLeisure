<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Tests\Factory\GrapeFactory;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GrapeRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $grape = static::getContainer()->get(GrapeFactory::class)->create();

        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        $this->assertSame($grape, $grapeRepository->find($grape->getId()));

        $grape->setName('grapeUpdate');

        $grapeRepository->update();

        $this->assertSame('grapeUpdate', $grapeRepository->find($grape->getId())->getName());

        $id = $grape->getId();
        $grapeRepository->delete($grape);

        $this->expectException(NotFoundHttpException::class);

        $grapeRepository->getFromUser($id, $grape->getUser()->getId());
    }
}
