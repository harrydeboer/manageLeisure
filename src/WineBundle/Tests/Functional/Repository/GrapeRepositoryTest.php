<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GrapeRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $grape = $this->createGrape($this->user);

        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        $this->assertSame($grape, $grapeRepository->find($grape->getId()));

        $grape->setName('test2');

        $grapeRepository->update();

        $this->assertSame('test2', $grapeRepository->find($grape->getId())->getName());

        $id = $grape->getId();
        $grapeRepository->delete($grape);

        $this->expectException(NotFoundHttpException::class);

        $grapeRepository->getFromUser($id, $this->user->getId());
    }
}
