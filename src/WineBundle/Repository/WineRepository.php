<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\Entity\User;
use App\Pagination\Paginator;
use App\WineBundle\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

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

    public function getFromUser(int $id, int $userId): Wine
    {
        $wine = $this->findOneBy(['id' => $id, 'user' => $userId]);

        if (is_null($wine)) {
            throw new NotFoundHttpException('This wine does not exist or does not belong to you.');
        }

        return $wine;
    }

    public function create(Wine $wine): Wine
    {
        $this->em->persist($wine);
        $this->em->flush();

        return $wine;
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

    public function findAllOfUser(User $user): array
    {
        $qb = $this->createQueryBuilder('w')
            ->where('w.user = ' . $user->getId());

        return $qb->getQuery()->execute();
    }

    /**
     * The wine homepage has a filtering and sorting of wines.
     * The filtering is on the current user, grapes, year, taste profile.
     * The sorting is on rating, price and creation time.
     * @throws Exception
     */
    public function findBySortAndFilter(
        User $user,
        int $page,
        array $formData = null,
    ): Paginator|array
    {
        $qb = $this->createQueryBuilder('w')
            ->where('w.user = ' . $user->getId());

        if (!is_null($formData)) {
            if (count($formData['grapes']) !== 0) {
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

            if (!is_null($formData['country'])) {
                $qb->andWhere('w.country = ' . $formData['country']->getId());
            }

            if (!is_null($formData['region'])) {
                $qb->andWhere('w.region = ' . $formData['region']->getId());
            }

            if (!is_null($formData['subregion'])) {
                $qb->andWhere('w.subregion = ' . $formData['subregion']->getId());
            }

            if (!is_null($formData['tasteProfile'])) {
                $qb->andWhere('w.tasteProfile = ' . $formData['tasteProfile']->getId());
            }

            $filterArray = explode('_', $formData['sort']);
            $qb->orderBy('w.' . $filterArray[0], $filterArray[1]);
        } else {
            $qb->orderBy('w.createdAt', 'DESC');
        }

        if ($page === 0) {
            return $qb->getQuery()->execute();
        }

        return (new Paginator($qb))->paginate($page);
    }
}
