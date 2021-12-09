<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\CreateWineForm;
use App\WineBundle\Form\DeleteWineForm;
use App\WineBundle\Form\UpdateWineForm;
use App\WineBundle\Form\WineFilterAndSortForm;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class WineController extends AuthController
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
        $form = $this->container->get('form.factory')->createNamed('', WineFilterAndSortForm::class, null, [
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
        $this->isAuthenticated($wine->getUser());

        $formUpdate = $this->createForm(UpdateWineForm::class, $wine, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteWineForm::class, $wine, [
            'action' => $this->generateUrl('wineDelete', ['id' => $wine->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        /**
         * When a wine is updated the uploaded image gets moved to the label directory when not testing.
         */
        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->wineRepository->update();
            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->moveLabel($formUpdate->get('label')->getData());
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

        /**
         * When a wine is created it gets a creation time.
         * The uploaded image gets moved to the label directory when not testing.
         */
        if ($form->isSubmitted() && $form->isValid()) {
            $wine->setUser($this->getCurrentUser());
            $wine->setCreatedAt(time());
            $this->wineRepository->create($wine);
            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->moveLabel($form->get('label')->getData());
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
        $this->isAuthenticated($wine->getUser());

        $form = $this->createForm(DeleteWineForm::class);
        $form->handleRequest($request);

        /**
         * When the submitted form is valid the wine is deleted along with its label when not testing.
         */
        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->kernel->getEnvironment() !== 'test') {
                $wine->unlinkLabel();
            }

            $this->wineRepository->delete($wine);
        }

        return $this->redirectToRoute('wineHomepage');
    }

    /**
     * @Route("/wine/single/{id}", name="wineSingle")
     */
    public function single(Wine $wine): Response
    {
        $this->isAuthenticated($wine->getUser());

        return $this->render('@Wine/wine/single/view.html.twig', [
            'wine' => $wine,
        ]);
    }

    /**
     * @Route("/wine/{notfound}", name="winePageNotFound")
     */
    public function pageNotFound(): void
    {
        throw new NotFoundHttpException('The page has not been found.');
    }
}
