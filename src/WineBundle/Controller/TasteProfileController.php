<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Form\DeleteTasteProfileType;
use App\WineBundle\Form\TasteProfileType;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasteProfileController extends AuthController
{
    public function __construct(
        private TasteProfileRepositoryInterface $tasteProfileRepository,
    ) {
    }

    /**
     * @Route("/wine/taste-profile", name="wineTasteProfile")
     */
    public function view(): Response
    {
        $tasteProfiles = $this->getCurrentUser()->getTasteProfiles();

        return $this->render('@WineBundle/tasteProfile/view.html.twig', [
            'tasteProfiles' => $tasteProfiles,
        ]);
    }

    /**
     * @Route("/wine/taste-profile/edit/{id}", name="wineTasteProfileEdit")
     */
    public function edit(Request $request, TasteProfile $tasteProfile): Response
    {
        $this->isAuthenticated($tasteProfile->getUser());

        $formUpdate = $this->createForm(TasteProfileType::class, $tasteProfile, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteTasteProfileType::class, $tasteProfile, [
            'action' => $this->generateUrl('wineTasteProfileDelete', ['id' => $tasteProfile->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->tasteProfileRepository->update();

            return $this->redirectToRoute('wineTasteProfile');
        }

        return $this->renderForm('@WineBundle/tasteProfile/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/taste-profile/create", name="wineTasteProfileCreate")
     */
    public function new(Request $request): Response
    {
        $tasteProfile = new TasteProfile();
        $form = $this->createForm(TasteProfileType::class, $tasteProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasteProfile->setUser($this->getCurrentUser());
            $this->tasteProfileRepository->create($tasteProfile);

            return $this->redirectToRoute('wineTasteProfile');
        }

        return $this->renderForm('@WineBundle/tasteProfile/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/taste-profile/delete/{id}", name="wineTasteProfileDelete")
     */
    public function delete(Request $request, TasteProfile $tasteProfile): RedirectResponse
    {
        $this->isAuthenticated($tasteProfile->getUser());

        $form = $this->createForm(DeleteTasteProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tasteProfileRepository->delete($tasteProfile);
        }

        return $this->redirectToRoute('wineTasteProfile');
    }

    /**
     * @Route("/wine/taste-profile/single/{id}", name="wineTasteProfileSingle")
     */
    public function single(TasteProfile $tasteProfile): Response
    {
        $this->isAuthenticated($tasteProfile->getUser());

        return $this->render('@Wine/tasteProfile/single/view.html.twig', [
            'tasteProfile' => $tasteProfile,
        ]);
    }
}
