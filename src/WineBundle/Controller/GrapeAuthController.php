<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Form\DeleteGrapeForm;
use App\WineBundle\Form\GrapeForm;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrapeAuthController extends AuthController
{
    public function __construct(
        private GrapeRepositoryInterface $grapeRepository,
    ) {
    }

    /**
     * @Route("/wine/grape", name="wineGrape")
     */
    public function view(): Response
    {
        $grapes = $this->getCurrentUser()->getGrapes();

        return $this->renderForm('@WineBundle/grape/view.html.twig', [
            'grapes' => $grapes,
        ]);
    }

    /**
     * @Route("/wine/grape/edit/{id}", name="wineGrapeEdit")
     */
    public function edit(Request $request, Grape $grape): Response
    {
        $this->isAuthenticated($grape->getUser());

        $formUpdate = $this->createForm(GrapeForm::class, $grape, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteGrapeForm::class, $grape, [
            'action' => $this->generateUrl('wineGrapeDelete', ['id' => $grape->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->grapeRepository->update();

            return $this->redirectToRoute('wineGrape');
        }

        return $this->renderForm('@WineBundle/grape/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/grape/create", name="wineGrapeCreate")
     */
    public function new(Request $request): Response
    {
        $grape = new Grape();
        $form = $this->createForm(GrapeForm::class, $grape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $grape->setUser($this->getCurrentUser());
            $this->grapeRepository->create($grape);

            return $this->redirectToRoute('wineGrape');
        }

        return $this->renderForm('@WineBundle/grape/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/grape/delete/{id}", name="wineGrapeDelete")
     */
    public function delete(Request $request, Grape $grape): RedirectResponse
    {
        $this->isAuthenticated($grape->getUser());

        $form = $this->createForm(DeleteGrapeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grapeRepository->delete($grape);
        }

        return $this->redirectToRoute('wineGrape');
    }
}
