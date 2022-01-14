<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends AuthController
{
    public function __construct(
        private RegionRepositoryInterface $regionRepository,
    ) {
    }

    /**
     * @Route("/get-regions/{id}", name="wineGetRegions")
     */
    public function getRegions(int $id = null): Response
    {
        if (is_null($id)) {
            return $this->render('@WineBundle/region/getRegions.html.twig', [
                'regions' => [],
            ]);
        }

        return $this->render('@WineBundle/region/getRegions.html.twig', [
            'regions' => $this->regionRepository->findAllOrderedByName($id),
        ]);
    }
}
