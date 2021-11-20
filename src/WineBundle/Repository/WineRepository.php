<?php

namespace App\WineBundle\Repository;

use App\Entity\User;
use App\Pagination\Paginator;
use App\WineBundle\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wine[]    findAll()
 * @method Wine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineRepository extends ServiceEntityRepository implements WineRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Wine::class);
    }

    public function create(Wine $wine): void
    {
        $this->em->persist($wine);
        $this->em->flush();
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Wine $wine): void
    {
        $this->em->remove($wine);
        $this->em->flush();
    }

    public function findLatest(User $user, int $page): Paginator
    {
        $qb = $this->createQueryBuilder('w')
            ->where('w.user = ' . $user->getId())
            ->orderBy('w.createdAt', 'DESC');

        return (new Paginator($qb))->paginate($page);
    }

    public function findBySortAndFilter(
        User $user,
        int $page,
        array $formData = null,
    ): Paginator
    {
        $qb = $this->createQueryBuilder('w')
            ->where('w.user = ' . $user->getId());

        if (!is_null($formData)) {
            if ($formData['grapes'] !== []) {
                $qb->innerJoin('w.grapes', 'g');
                $ids = '';
                foreach ($formData['grapes'] as $grape) {
                    $ids .= $grape->getId() . ',';
                }
                $ids = rtrim($ids, ',');
                $qb->andWhere('g.id IN (' . $ids . ')');
            }

            if (!is_null($formData['year'])) {
                $qb->andWhere('w.year = ' . $formData['year']);
            }

            if (!is_null($formData['category'])) {
                $qb->andWhere('w.category = ' . $formData['category']->getId());
            }

            $filterArray = explode('_', $formData['filter']);
            $qb->orderBy('w.' . $filterArray[0], $filterArray[1]);
        } else {
            $qb->orderBy('w.createdAt', 'DESC');
        }

        return (new Paginator($qb))->paginate($page);
    }
}
