<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Elasticsearch\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends AbstractController
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    public function catchAll(Request $request): Response
    {
        $uri = explode('/', $request->getUri())[3];
        if ($uri) {
            if ($uri === 'home') {
                return $this->redirectToRoute('homepage');
            }
            return $this->render('page/view.html.twig', [
                'page' => $this->pageRepository->getBySlug($uri),
            ]);
        }

        throw new NotFoundHttpException('The page has not been found.');
    }
}
