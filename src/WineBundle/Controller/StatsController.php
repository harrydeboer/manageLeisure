<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Form\WineFilterAndSortType;
use App\WineBundle\Repository\WineRepositoryInterface;
use App\WineBundle\Service\StatsCalculator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AuthController
{
    public function __construct(
        private WineRepositoryInterface $wineRepository,
        private FormFactoryInterface $formFactory,
    ) {
    }

    #[Route('/stats', name: 'wineStats')]
    public function view(Request $request): Response
    {
        $form = $this->formFactory->createNamed('', WineFilterAndSortType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
            'country' => $request->get('country'),
            'region' => $request->get('region'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wines = $this->wineRepository->findBySortAndFilter($this->getUser(),0, $form->getData());
        } else {
            $wines = $this->wineRepository->findAll();
        }

        return $this->renderForm('@WineBundle/stats/view.html.twig', [
            'form' => $form,
            'average' => StatsCalculator::average($wines),
            'pieChart' => StatsCalculator::pieChart($wines),
        ]);
    }
}
