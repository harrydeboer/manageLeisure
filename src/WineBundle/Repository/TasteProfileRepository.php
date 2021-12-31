<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\TasteProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function getFromUser(int $id, int $userId): TasteProfile
    {
        $tasteProfile = $this->findOneBy(['id' => $id, 'user' => $userId]);

        if (is_null($tasteProfile)) {
            throw new NotFoundHttpException('This taste profile does not exist or does not belong to you.');
        }

        return $tasteProfile;
    }

    public function create(TasteProfile $tasteProfile): TasteProfile
    {
        $this->em->persist($tasteProfile);
        $this->em->flush();

        return $tasteProfile;
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
