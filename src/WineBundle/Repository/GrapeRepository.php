<?php

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Grape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grape|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grape|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grape[]    findAll()
 * @method Grape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrapeRepository extends ServiceEntityRepository implements GrapeRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Grape::class);
    }

    public function create(Grape $grape): void
    {
        $this->em->persist($grape);
        $this->em->flush();
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Grape $grape): void
    {
        $this->em->remove($grape);
        $this->em->flush();
    }
}
