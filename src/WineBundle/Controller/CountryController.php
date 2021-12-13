<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Repository\CountryRepositoryInterface;
use App\WineBundle\Entity\Country;
use App\WineBundle\Form\DeleteCountryType;
use App\WineBundle\Form\CountryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AuthController
{
    public function __construct(
        private CountryRepositoryInterface $countryRepository,
    ) {
    }

    /**
     * @Route("/wine/country", name="wineCountry")
     */
    public function view(): Response
    {
        $countries = $this->countryRepository->findOrderedByName($this->getUser());

        return $this->render('@WineBundle/country/view.html.twig', [
            'countries' => $countries,
        ]);
    }

    /**
     * @Route("/wine/country/edit/{id}", name="wineCountryEdit")
     */
    public function edit(Request $request, int $id): Response
    {
        $country = $this->getCountry($id);

        $formUpdate = $this->createForm(CountryType::class, $country, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteCountryType::class, $country, [
            'action' => $this->generateUrl('wineCountryDelete', ['id' => $country->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->countryRepository->update();

            return $this->redirectToRoute('wineCountry');
        }

        return $this->renderForm('@WineBundle/country/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/country/create", name="wineCountryCreate")
     */
    public function new(Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setUser($this->getUser());
            $this->countryRepository->create($country);

            return $this->redirectToRoute('wineCountry');
        }

        return $this->renderForm('@WineBundle/country/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/country/delete/{id}", name="wineCountryDelete")
     */
    public function delete(Request $request, int $id): RedirectResponse
    {
        $country = $this->getCountry($id);

        $form = $this->createForm(DeleteCountryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->countryRepository->delete($country);
        }

        return $this->redirectToRoute('wineCountry');
    }

    /**
     * @Route("/wine/country/get-regions/{id}", name="wineGetRegions")
     */
    public function getRegions(int $id = null): Response
    {
        if (is_null($id)) {
            return $this->render('@WineBundle/country/getRegions.html.twig', [
                'regions' => [],
            ]);
        }

        $country = $this->getCountry($id);

        return $this->render('@WineBundle/country/getRegions.html.twig', [
            'regions' => $country->getRegions(),
        ]);
    }

    private function getCountry(int $id): Country
    {
        return $this->countryRepository->getFromUser($id, $this->getUser()->getId());
    }
}
