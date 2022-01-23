<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function view(): Response
    {
        return $this->render('homepage/view.html.twig', [
            'page' => $this->pageRepository->getByTitle('Home'),
        ]);
    }
}
