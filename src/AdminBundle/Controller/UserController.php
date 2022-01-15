<?php

declare(strict_types=1);

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\CreateUserType;
use App\AdminBundle\Form\DeleteUserType;
use App\AdminBundle\Form\UpdateUserType;
use App\Controller\AuthController;
use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AuthController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @Route("/user", name="adminUser")
     */
    public function view(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('@AdminBundle/user/view.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="adminUserEdit")
     */
    public function edit(Request $request, User $user): Response
    {
        $formUpdate = $this->createForm(UpdateUserType::class, $user, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteUserType::class, $user, [
            'action' => $this->generateUrl('adminUserDelete', ['id' => $user->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            if (is_null($formUpdate->get('plainPassword')->getData())) {
                $this->userRepository->update();
            } else {
                $this->userRepository->upgradePassword($user, $formUpdate->get('plainPassword')->getData());
            }

            return $this->redirectToRoute('adminUser');
        }

        return $this->renderForm('@AdminBundle/user/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/user/create", name="adminUserCreate")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->create($user, $form->get('plainPassword')->getData());

            return $this->redirectToRoute('adminUser');
        }

        return $this->renderForm('@AdminBundle/user/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="adminUserDelete")
     */
    public function delete(Request $request, User $user): RedirectResponse
    {
        $form = $this->createForm(DeleteUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->delete($user);
        }

        return $this->redirectToRoute('adminUser');
    }
}
