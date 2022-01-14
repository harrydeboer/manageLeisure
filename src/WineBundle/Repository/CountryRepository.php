<?php

declare(strict_types=1);

namespace App\WineBundle\Repository;

use App\WineBundle\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository implements CountryRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Country::class);
    }

    public function get(int $id): Country
    {
        $country = $this->findOneBy(['id' => $id]);

        if (is_null($country)) {
            throw new NotFoundHttpException('This country does not exist or does not belong to you.');
        }

        return $country;
    }

    public function create(Country $country): Country
    {
        $this->em->persist($country);
        $this->em->flush();

        return $country;
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Country $country): void
    {
        $this->em->remove($country);
        $this->em->flush();
    }

    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');

        return $qb->getQuery()->execute();
    }
}
