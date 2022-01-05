<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Factory\MailUserFactory;
use App\Repository\MailUserRepositoryInterface;
use App\Tests\Functional\KernelTestCase;

class MailUserRepositoryTest extends KernelTestCase
{
    public function testCreateUpdateDelete()
    {
        $mailUser = static::getContainer()->get(MailUserFactory::class)->create();

        $mailUserRepository = static::getContainer()->get(MailUserRepositoryInterface::class);

        $this->assertSame($mailUser, $mailUserRepository->find($mailUser->getId()));

        $updatedDomain = 'test2.com';
        $mailUser->setDomain($updatedDomain);

        $mailUserRepository->update($mailUser, 'newPassword');

        $this->assertSame($updatedDomain, $mailUserRepository->find($mailUser->getId())->getDomain());

        $id = $mailUser->getId();
        $mailUserRepository->delete($mailUser);

        $this->assertNull($mailUserRepository->find($id));
    }
}
