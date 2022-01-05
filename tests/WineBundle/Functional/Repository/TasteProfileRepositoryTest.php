<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Factory\TasteProfileFactory;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TasteProfileRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $tasteProfile = static::getContainer()->get(TasteProfileFactory::class)->create();

        $tasteProfileRepository = static::getContainer()->get(TasteProfileRepositoryInterface::class);

        $this->assertSame($tasteProfile, $tasteProfileRepository->find($tasteProfile->getId()));

        $updatedName = 'tasteProfileUpdate';
        $tasteProfile->setName($updatedName);

        $tasteProfileRepository->update();

        $this->assertSame($updatedName, $tasteProfileRepository->find($tasteProfile->getId())->getName());

        $id = $tasteProfile->getId();
        $tasteProfileRepository->delete($tasteProfile);

        $this->expectException(NotFoundHttpException::class);

        $tasteProfileRepository->getFromUser($id, $tasteProfile->getUser()->getId());
    }
}
