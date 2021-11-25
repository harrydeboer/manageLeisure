<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Repository;

use App\Tests\Feature\AuthRepositoryTestCase;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Error;

class GrapeRepositoryTest extends AuthRepositoryTestCase
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

        $grapeRepository->delete($grape);

        $this->expectException(Error::class);

        $grapeRepository->find($grape->getId());
    }
}
