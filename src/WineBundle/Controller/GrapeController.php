<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\AuthController;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Form\DeleteGrapeType;
use App\WineBundle\Form\GrapeType;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrapeController extends AuthController
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
        $grapes = $this->getUser()->getGrapes();

        return $this->renderForm('@WineBundle/grape/view.html.twig', [
            'grapes' => $grapes,
        ]);
    }

    /**
     * @Route("/wine/grape/edit/{id}", name="wineGrapeEdit")
     */
    public function edit(Request $request, int $id): Response
    {
        $grape = $this->getGrape($id);

        $formUpdate = $this->createForm(GrapeType::class, $grape, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteGrapeType::class, $grape, [
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
        $form = $this->createForm(GrapeType::class, $grape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $grape->setUser($this->getUser());
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
    public function delete(Request $request, int $id): RedirectResponse
    {
        $grape = $this->getGrape($id);

        $form = $this->createForm(DeleteGrapeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grapeRepository->delete($grape);
        }

        return $this->redirectToRoute('wineGrape');
    }

    private function getGrape(int $id): Grape
    {
        return $this->grapeRepository->getFromUser($id, $this->getUser()->getId());
    }
}
