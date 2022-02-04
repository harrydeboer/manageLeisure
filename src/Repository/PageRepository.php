<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Page::class);
    }

    public function getFromUser(int $id, int $userId): Page
    {
        $page = $this->findOneBy(['id' => $id, 'user' => $userId]);

        if (is_null($page)) {
            throw new NotFoundHttpException('This page does not exist or does not belong to you.');
        }

        return $page;
    }

    public function getBySlug(string $slug): Page
    {
        $page = $this->findOneBy(['slug' => $slug]);

        if (is_null($page)) {
            throw new NotFoundHttpException('This page does not exist or does not belong to you.');
        }

        return $page;
    }

    public function create(Page $page): Page
    {
        $this->em->persist($page);
        $this->em->flush();

        return $page;
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Page $page): void
    {
        $this->em->remove($page);
        $this->em->flush();
    }
}
