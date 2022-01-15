<?php

declare(strict_types=1);

namespace App\AdminBundle\Repository;

use App\AdminBundle\Entity\MailUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface MailUserRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(MailUser $mailUser): MailUser;

    public function update(MailUser $mailUser, ?string $newPassword): void;

    public function delete(MailUser $mailUser): void;
}
