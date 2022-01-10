<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Subregion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Subregion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subregion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subregion[]    findAll()
 * @method Subregion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubregionRepository extends ServiceEntityRepository implements SubregionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Subregion::class);
    }

    public function get(int $id): Subregion
    {
        $region = $this->findOneBy(['id' => $id]);

        if (is_null($region)) {
            throw new NotFoundHttpException('This subregion does not exist or does not belong to you.');
        }

        return $region;
    }

    public function create(Subregion $subregion): Subregion
    {
        $this->em->persist($subregion);
        $this->em->flush();

        return $subregion;
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Subregion $subregion): void
    {
        $this->em->remove($subregion);
        $this->em->flush();
    }
}
