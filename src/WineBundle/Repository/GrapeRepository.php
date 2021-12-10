<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Grape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
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

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        $grape = $this->em->find(Grape::class, $id);

        if (is_null($grape)) {
            throw new NotFoundHttpException('This grape does not exist.');
        }

        return $grape;
    }

    public function create(Grape $grape): Grape
    {
        $this->em->persist($grape);
        $this->em->flush();

        return $grape;
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
