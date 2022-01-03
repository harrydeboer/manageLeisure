<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Repository;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Tests\Factory\TasteProfileFactory;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TasteProfileRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $tasteProfile = static::getContainer()->get(TasteProfileFactory::class)->create();

        $tasteProfileRepository = static::getContainer()->get(TasteProfileRepositoryInterface::class);

        $this->assertSame($tasteProfile, $tasteProfileRepository->find($tasteProfile->getId()));

        $tasteProfile->setName('tasteProfileUpdate');

        $tasteProfileRepository->update();

        $this->assertSame('tasteProfileUpdate', $tasteProfileRepository->find($tasteProfile->getId())->getName());

        $id = $tasteProfile->getId();
        $tasteProfileRepository->delete($tasteProfile);

        $this->expectException(NotFoundHttpException::class);

        $tasteProfileRepository->getFromUser($id, $tasteProfile->getUser()->getId());
    }
}
