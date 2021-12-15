<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface MailUserRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(MailUser $mailUser): MailUser;

    public function update(): void;

    public function delete(MailUser $mailUser): void;
}
