<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository implements RegionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Region::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        $region = $this->em->find(Region::class, $id);

        if (is_null($region)) {
            throw new NotFoundHttpException('This region does not exist.');
        }

        return $region;
    }

    public function create(Region $region): void
    {
        $this->em->persist($region);
        $this->em->flush();
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Region $region): void
    {
        $this->em->remove($region);
        $this->em->flush();
    }
}
