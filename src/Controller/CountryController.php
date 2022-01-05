<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CountryRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AuthController
{
    public function __construct(
        private CountryRepositoryInterface $countryRepository,
    ) {
    }

    /**
     * @Route("/country/get-regions/{id}", name="wineGetRegions")
     */
    public function getRegions(int $id = null): Response
    {
        if (is_null($id)) {
            return $this->render('country/getRegions.html.twig', [
                'regions' => [],
            ]);
        }

        $country = $this->countryRepository->get($id);

        return $this->render('country/getRegions.html.twig', [
            'regions' => $country->getRegions(),
        ]);
    }
}
