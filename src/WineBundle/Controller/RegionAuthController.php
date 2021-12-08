<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\Region;
use App\WineBundle\Form\DeleteRegionForm;
use App\WineBundle\Form\RegionForm;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionAuthController extends AuthController
{
    public function __construct(
        private RegionRepositoryInterface $regionRepository,
    ) {
    }

    /**
     * @Route("/wine/region", name="wineRegion")
     */
    public function view(): Response
    {
        $regions = $this->getCurrentUser()->getRegions();

        return $this->render('@WineBundle/region/view.html.twig', [
            'regions' => $regions,
        ]);
    }

    /**
     * @Route("/wine/region/edit/{id}", name="wineRegionEdit")
     */
    public function edit(Request $request, Region $region): Response
    {
        $this->isAuthenticated($region->getUser());

        $formUpdate = $this->createForm(RegionForm::class, $region, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteRegionForm::class, $region, [
            'action' => $this->generateUrl('wineRegionDelete', ['id' => $region->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->regionRepository->update();

            return $this->redirectToRoute('wineRegion');
        }

        return $this->renderForm('@WineBundle/region/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/region/create", name="wineRegionCreate")
     */
    public function new(Request $request): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionForm::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $region->setUser($this->getCurrentUser());
            $this->regionRepository->create($region);

            return $this->redirectToRoute('wineRegion');
        }

        return $this->renderForm('@WineBundle/region/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/region/delete/{id}", name="wineRegionDelete")
     */
    public function delete(Request $request, Region $region): RedirectResponse
    {
        $this->isAuthenticated($region->getUser());

        $form = $this->createForm(DeleteRegionForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->regionRepository->delete($region);
        }

        return $this->redirectToRoute('wineRegion');
    }
}
