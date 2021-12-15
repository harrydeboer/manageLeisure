<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailUser[]    findAll()
 * @method MailUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailUserRepository extends ServiceEntityRepository implements MailUserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, MailUser::class);
    }

    public function create(MailUser $mailUser): MailUser
    {
        $this->em->persist($mailUser);
        $this->em->flush();

        return $mailUser;
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(MailUser $mailUser): void
    {
        $this->em->remove($mailUser);
        $this->em->flush();
    }
}
