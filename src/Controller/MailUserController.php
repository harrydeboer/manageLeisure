<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MailUser;
use App\Form\CreateMailUserType;
use App\Form\DeleteMailUserType;
use App\Form\UpdateMailUserType;
use App\Repository\MailUserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailUserController extends AuthController
{
    public function __construct(
        private MailUserRepositoryInterface $mailUserRepository,
    ) {
    }

    /**
     * @Route("/mail-user", name="mailUser")
     */
    public function view(): Response
    {
        $mailUsers = $this->mailUserRepository->findAll();

        return $this->render('mailUser/view.html.twig', [
            'mailUsers' => $mailUsers,
        ]);
    }

    /**
     * @Route("/mail-user/edit/{id}", name="mailUserEdit")
     */
    public function edit(Request $request, MailUser $mailUser): Response
    {
        $formUpdate = $this->createForm(UpdateMailUserType::class, $mailUser, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteMailUserType::class, $mailUser, [
            'action' => $this->generateUrl('mailUserDelete', ['id' => $mailUser->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->mailUserRepository->update($mailUser, $formUpdate->get('newPassword')->getData());

            return $this->redirectToRoute('mailUser');
        }

        return $this->renderForm('mailUser/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/mail-user/create", name="mailUserCreate")
     */
    public function new(Request $request): Response
    {
        $mailUser = new MailUser();
        $form = $this->createForm(CreateMailUserType::class, $mailUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailUserRepository->create($mailUser);

            return $this->redirectToRoute('mailUser');
        }

        return $this->renderForm('mailUser/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/mail-user/delete/{id}", name="mailUserDelete")
     */
    public function delete(Request $request, MailUser $mailUser): RedirectResponse
    {
        $form = $this->createForm(DeleteMailUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailUserRepository->delete($mailUser);
        }

        return $this->redirectToRoute('mailUser');
    }
}
