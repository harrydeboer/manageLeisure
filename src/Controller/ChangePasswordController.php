<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangePasswordForm;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AuthController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @Route("/change-password", name="changePassword")
     */
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userRepository->upgradePassword($this->getCurrentUser(), $form->get('plainPassword')->getData());

            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('/security/changePassword.html.twig', [
            'form' => $form,
        ]);
    }
}
