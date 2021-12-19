<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\VerifyType;
use App\Repository\UserRepositoryInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private KernelInterface $kernel,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @Route("/register", name="app_register")
     * @throws TransportExceptionInterface
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userRepository->create($user, $form->get('plainPassword')->getData());

            $this->sendVerificationMail($user);

            $this->addFlash('mustVerify', 'Your email address has to be verified. Check your inbox.');

            $token = new UsernamePasswordToken($user, $user->getPassword(), $user->getRoles());

            $this->tokenStorage->setToken($token);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Do not get the User's id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), (string) $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true

        $this->addFlash('success', 'Your email address has been verified.');

        $this->getUser()->setIsVerified(true);
        $this->userRepository->update();

        $token = new UsernamePasswordToken($user, $user->getPassword(), $user->getRoles());

        $this->tokenStorage->setToken($token);

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/send-verification-email-again", name="sendVerificationEmailAgain")
     * @throws TransportExceptionInterface
     */
    public function sendVerificationEmailAgain(Request $request): Response
    {
        $success = null;
        $error = null;

        $form = $this->createForm(VerifyType::class);
        $form->handleRequest($request);

        if ($this->getUser()->isVerified()) {
            $success = 'Your email is already verified.';
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $result = $this->sendVerificationMail($this->getUser());
            if ($result) {
                $success = 'Successfully send a verification mail.';
            } else {
                $error = 'Could not send mail.';
            }
        }

        return $this->renderForm('registration/sendVerificationEmailAgain.html.twig', [
            'form' => $form,
            'success' => $success,
            'error' => $error,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendVerificationMail(UserInterface $user): bool
    {
        if ($this->kernel->getEnvironment() === 'prod') {

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                (string) $user->getId(),
                $user->getEmail()
            );

            $email = new TemplatedEmail();
            $email->from(new Address('noreply@manageleisure.com', 'Manage Leisure'));
            $email->to($user->getEmail());
            $email->subject('Verify your email on manageleisure.com');
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

            $this->mailer->send($email);

            return true;
        }

        return false;
    }
}
