<?php

declare(strict_types=1);

namespace App\Repository;

use App\AdminBundle\Entity\MailUser;
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
        $mailUser->setPassword($this->encryptPasswordSHA512($mailUser->getPassword()));
        $this->em->persist($mailUser);
        $this->em->flush();

        return $mailUser;
    }

    public function update(MailUser $mailUser, ?string $newPassword): void
    {
        if (!is_null($newPassword)) {
            $mailUser->setPassword($this->encryptPasswordSHA512($newPassword));
        }

        $this->em->flush();
    }

    public function delete(MailUser $mailUser): void
    {
        $this->em->remove($mailUser);
        $this->em->flush();
    }

    private function encryptPasswordSHA512(string $password):string
    {
        $saltLength = 50;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $salt = '';
        for ($i = 0; $i < $saltLength; $i++) {
            $salt .= $characters[rand(0, $charactersLength - 1)];
        }

        return crypt($password, '$6$' . $salt);
    }
}
