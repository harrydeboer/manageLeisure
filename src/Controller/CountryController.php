<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CountryRepositoryInterface;
use App\Entity\Country;
use App\Form\DeleteCountryType;
use App\Form\CountryType;
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
     * @Route("/country", name="country")
     */
    public function view(): Response
    {
        $countries = $this->countryRepository->findOrderedByName($this->getCurrentUser());

        return $this->render('country/view.html.twig', [
            'countries' => $countries,
        ]);
    }

    /**
     * @Route("/country/edit/{id}", name="countryEdit")
     */
    public function edit(Request $request, Country $country): Response
    {
        $this->isAuthenticated($country->getUser());

        $formUpdate = $this->createForm(CountryType::class, $country, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteCountryType::class, $country, [
            'action' => $this->generateUrl('countryDelete', ['id' => $country->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->countryRepository->update();

            return $this->redirectToRoute('country');
        }

        return $this->renderForm('country/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/country/create", name="countryCreate")
     */
    public function new(Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setUser($this->getCurrentUser());
            $this->countryRepository->create($country);

            return $this->redirectToRoute('country');
        }

        return $this->renderForm('country/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/country/delete/{id}", name="countryDelete")
     */
    public function delete(Request $request, Country $country): RedirectResponse
    {
        $this->isAuthenticated($country->getUser());

        $form = $this->createForm(DeleteCountryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->countryRepository->delete($country);
        }

        return $this->redirectToRoute('country');
    }
}
