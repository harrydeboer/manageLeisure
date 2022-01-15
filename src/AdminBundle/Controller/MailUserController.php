<?php

declare(strict_types=1);

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\MailUser;
use App\AdminBundle\Form\CreateMailUserType;
use App\AdminBundle\Form\DeleteMailUserType;
use App\AdminBundle\Form\UpdateMailUserType;
use App\AdminBundle\Repository\MailUserRepositoryInterface;
use App\Controller\AuthController;
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
     * @Route("/mail-user", name="adminMailUser")
     */
    public function view(): Response
    {
        $mailUsers = $this->mailUserRepository->findAll();

        return $this->render('@AdminBundle/mailUser/view.html.twig', [
            'mailUsers' => $mailUsers,
        ]);
    }

    /**
     * @Route("/mail-user/edit/{id}", name="adminMailUserEdit")
     */
    public function edit(Request $request, MailUser $mailUser): Response
    {
        $formUpdate = $this->createForm(UpdateMailUserType::class, $mailUser, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteMailUserType::class, $mailUser, [
            'action' => $this->generateUrl('adminMailUserDelete', ['id' => $mailUser->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->mailUserRepository->update($mailUser, $formUpdate->get('newPassword')->getData());

            return $this->redirectToRoute('adminMailUser');
        }

        return $this->renderForm('@AdminBundle/mailUser/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/mail-user/create", name="adminMailUserCreate")
     */
    public function new(Request $request): Response
    {
        $mailUser = new MailUser();
        $form = $this->createForm(CreateMailUserType::class, $mailUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailUserRepository->create($mailUser);

            return $this->redirectToRoute('adminMailUser');
        }

        return $this->renderForm('@AdminBundle/mailUser/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/mail-user/delete/{id}", name="adminMailUserDelete")
     */
    public function delete(Request $request, MailUser $mailUser): RedirectResponse
    {
        $form = $this->createForm(DeleteMailUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailUserRepository->delete($mailUser);
        }

        return $this->redirectToRoute('adminMailUser');
    }
}
