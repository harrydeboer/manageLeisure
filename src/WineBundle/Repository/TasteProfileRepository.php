<?php

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\TasteProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TasteProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method TasteProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method TasteProfile[]    findAll()
 * @method TasteProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasteProfileRepository extends ServiceEntityRepository implements TasteProfileRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, TasteProfile::class);
    }

    public function create(TasteProfile $tasteProfile): void
    {
        $this->em->persist($tasteProfile);
        $this->em->flush();
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(TasteProfile $tasteProfile): void
    {
        $this->em->remove($tasteProfile);
        $this->em->flush();
    }
}
