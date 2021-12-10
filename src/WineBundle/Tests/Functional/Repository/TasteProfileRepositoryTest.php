<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\AuthKernelTestCase;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TasteProfileRepositoryTest extends AuthKernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $tasteProfile = new TasteProfile();
        $tasteProfile->setName('fresh');
        $tasteProfile->setUser($this->user);

        $tasteProfileRepository = static::getContainer()->get(TasteProfileRepositoryInterface::class);
        $tasteProfileRepository->create($tasteProfile);

        $this->assertSame($tasteProfile, $tasteProfileRepository->find($tasteProfile->getId()));

        $tasteProfile->setName('test2');

        $tasteProfileRepository->update();

        $this->assertSame('test2', $tasteProfileRepository->find($tasteProfile->getId())->getName());

        $id = $tasteProfile->getId();
        $tasteProfileRepository->delete($tasteProfile);

        $this->expectException(NotFoundHttpException::class);

        $tasteProfileRepository->find($id);
    }
}
