<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\DeleteWineType;
use App\WineBundle\Form\UpdateWineType;
use App\WineBundle\Form\WineFilterAndSortType;
use App\WineBundle\Form\WineType;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;

class WineController extends AuthController
{
    public function __construct(
        private WineRepositoryInterface $wineRepository,
        private FormFactoryInterface $formFactory,
        private KernelInterface $kernel,
    ) {
    }

    /**
     * @Route("/wine", defaults={"page": "1"}, name="wineHomepage")
     * @Route("/page/{page<[1-9]\d*>}", methods="GET", name="wine_index_paginated")
     */
    public function view(Request $request, int $page): Response
    {
        $form = $this->formFactory->createNamed('', WineFilterAndSortType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
            'country' => $request->get('country'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wines = $this->wineRepository->findBySortAndFilter($this->getUser(), $page, $form->getData());
        } else {
            $wines = $this->wineRepository->findBySortAndFilter($this->getUser(), $page);
        }

        return $this->renderForm('@WineBundle/wine/view.html.twig', [
            'paginator' => $wines,
            'appEnv' => $this->kernel->getEnvironment(),
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/edit/{id}", name="wineEdit")
     */
    public function edit(Request $request, int $id): Response
    {
        $wine = $this->getWine($id);

        $formUpdate = $this->createForm(UpdateWineType::class, $wine, [
            'method' => 'POST',
            'country' => isset($request->get('update_wine')['country']) ?
                $request->get('update_wine')['country'] : $wine->getCountry()->getId(),
        ]);

        $formDelete = $this->createForm(DeleteWineType::class, $wine, [
            'action' => $this->generateUrl('wineDelete', ['id' => $wine->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        /**
         * When a wine is updated the uploaded image gets moved to the label directory when not testing.
         */
        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            if ($wine->getCountry() !== $wine->getRegion()->getCountry()) {
                throw new ValidatorException('The region does not belong to the country.');
            }

            $this->wineRepository->update();
            $this->moveLabel($wine, $formUpdate->get('label')->getData());

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
        $form = $this->createForm(WineType::class, $wine, [
            'country' => is_null($request->get('wine')) ? null : $request->get('wine')['country'],
        ]);
        $form->handleRequest($request);

        /**
         * When a wine is created it gets a creation time.
         * The uploaded image gets moved to the label directory when not testing.
         */
        if ($form->isSubmitted() && $form->isValid()) {
            if ($wine->getCountry() !== $wine->getRegion()->getCountry()) {
                throw new ValidatorException('The region does not belong to the country.');
            }
            $wine->setUser($this->getUser());
            $wine->setCreatedAt(time());
            $this->wineRepository->create($wine);
            $this->moveLabel($wine, $form->get('label')->getData());

            return $this->redirectToRoute('wineHomepage');
        }

        return $this->renderForm('@WineBundle/wine/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/delete/{id}", name="wineDelete")
     */
    public function delete(Request $request, int $id): RedirectResponse
    {
        $wine = $this->getWine($id);

        $form = $this->createForm(DeleteWineType::class);
        $form->handleRequest($request);

        /**
         * When the submitted form is valid the wine is deleted along with its label when not testing.
         */
        if ($form->isSubmitted() && $form->isValid()) {

            $wine->unlinkLabel($this->kernel->getEnvironment(), $this->kernel->getProjectDir());

            $this->wineRepository->delete($wine);
        }

        return $this->redirectToRoute('wineHomepage');
    }

    /**
     * @Route("/wine/single/{id}", name="wineSingle")
     */
    public function single(int $id): Response
    {
        $wine = $this->getWine($id);

        return $this->render('@Wine/wine/single/view.html.twig', [
            'wine' => $wine,
            'appEnv' => $this->kernel->getEnvironment(),
        ]);
    }

    private function getWine(int $id): Wine
    {
        return $this->wineRepository->getFromUser($id, $this->getUser()->getId());
    }

    private function moveLabel(Wine $wine, ?UploadedFile $label)
    {
        $wine->moveLabel($label, $this->kernel->getEnvironment(), $this->kernel->getProjectDir());
    }
}
