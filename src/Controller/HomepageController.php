<?php

declare(strict_types=1);

namespace App\Controller;

use Elastica\Query;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class HomepageController extends AbstractController
{
    public function __construct(
        private PaginatedFinderInterface $finder,
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function view(): Response
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'title' => 'Homez',
                ],
            ],
        ]);

        return $this->render('homepage/view.html.twig', [
            'page' => $this->finder->find($query)[0],
        ]);
    }
}
