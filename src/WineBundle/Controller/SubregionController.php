<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Repository\SubregionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubregionController extends AuthController
{
    public function __construct(
        private SubregionRepositoryInterface $subregionRepository,
    ) {
    }

    /**
     * @Route("/wine/get-subregions/{id}", name="wineGetSubregions")
     */
    public function getSubregions(int $id = null): Response
    {
        if (is_null($id)) {
            return $this->render('@WineBundle/subregion/getSubregions.html.twig', [
                'subregions' => [],
            ]);
        }

        return $this->render('@WineBundle/subregion/getSubregions.html.twig', [
            'subregions' => $this->subregionRepository->findAllOrderedByName($id),
        ]);
    }
}
