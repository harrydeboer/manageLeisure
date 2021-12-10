<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GrapeRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $grape = new Grape();
        $grape->setName('test');
        $grape->setType('red');
        $grape->setUser($this->user);

        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);
        $grapeRepository->create($grape);

        $this->assertSame($grape, $grapeRepository->find($grape->getId()));

        $grape->setName('test2');

        $grapeRepository->update();

        $this->assertSame('test2', $grapeRepository->find($grape->getId())->getName());

        $id = $grape->getId();
        $grapeRepository->delete($grape);

        $this->expectException(NotFoundHttpException::class);

        $grapeRepository->find($id);
    }
}
