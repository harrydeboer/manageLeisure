<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\TasteProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
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

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        $tasteProfile = $this->em->find(TasteProfile::class, $id);

        if (is_null($tasteProfile)) {
            throw new NotFoundHttpException('This taste profile does not exist.');
        }

        return $tasteProfile;
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
