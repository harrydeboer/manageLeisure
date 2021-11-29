<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\Controller;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\CreateWineForm;
use App\WineBundle\Form\DeleteWineForm;
use App\WineBundle\Form\UpdateWineForm;
use App\WineBundle\Form\WineFilterAndSortForm;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class WineController extends Controller
{
    public function __construct(
        private KernelInterface $kernel,
        private WineRepositoryInterface $wineRepository,
    ) {
    }

    /**
     * @Route("/wine", defaults={"page": "1"}, name="wineHomepage")
     * @Route("/page/{page<[1-9]\d*>}", methods="GET", name="wine_index_paginated")
     */
    public function view(Request $request, int $page): Response
    {
        $form = $this->get('form.factory')->createNamed('', WineFilterAndSortForm::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wines = $this->wineRepository->findBySortAndFilter($this->getCurrentUser(), $page, $form->getData());
        } else {
            $wines = $this->wineRepository->findBySortAndFilter($this->getCurrentUser(), $page);
        }

        return $this->renderForm('@WineBundle/wine/view.html.twig', [
            'paginator' => $wines,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/edit/{id}", name="wineEdit")
     */
    public function edit(Request $request, Wine $wine): Response
    {
        $formUpdate = $this->createForm(UpdateWineForm::class, $wine, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteWineForm::class, $wine, [
            'action' => $this->generateUrl('wineDelete', ['id' => $wine->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->isAuthenticated($wine->getUser());
            $this->wineRepository->update();
            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->moveImage($formUpdate->get('image')->getData());
            }

            return $this->redirectToRoute('wineHomepage');
        }

        return $this->renderForm('@WineBundle/wine/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/create", name="wineCreate")
     */
    public function new(Request $request): Response
    {
        $wine = new Wine();
        $form = $this->createForm(CreateWineForm::class, $wine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wine->setUser($this->getCurrentUser());
            $wine->setCreatedAt(time());
            $this->wineRepository->create($wine);
            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->moveImage($form->get('image')->getData());
            }

            return $this->redirectToRoute('wineHomepage');
        }

        return $this->renderForm('@WineBundle/wine/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/delete/{id}", name="wineDelete")
     */
    public function delete(Request $request, Wine $wine): RedirectResponse
    {
        $form = $this->createForm(DeleteWineForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->isAuthenticated($wine->getUser());

            $this->wineRepository->delete($wine);
            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->unlinkImage();
            }
        }

        return $this->redirectToRoute('wineHomepage');
    }
}
