<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Exception\ValidatorException;

class ContactController extends AbstractController
{
    public function __construct(
        private MailerInterface $mailer,
        private KernelInterface $kernel,
    ) {

    }

    /**
     * @Route("/contact", name="contact")
     */
    public function view(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        $success = null;
        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->kernel->getEnvironment() !== 'test') {
                $this->validateReCaptcha($form->get('reCaptchaToken')->getData());
            }

            $email = (new Email())
                ->from(new Address('postmaster@manageleisure.com', $form->get('name')->getData()))
                ->replyTo($form->get('email')->getData())
                ->to('info@manageleisure.com')
                ->subject(strip_tags($form->get('subject')->getData()))
                ->html($form->get('message')->getData());
            $success = true;

            if ($this->kernel->getEnvironment() === 'prod') {
                try {
                    $this->mailer->send($email);
                } catch (TransportExceptionInterface) {
                    $success = false;
                }
            } else {
                $success = false;
            }
        }

        return $this->render('contact/view.html.twig', [
            'form' => $form->createView(),
            'reCaptchaKey' => $this->getParameter('re_captcha_key'),
            'success' => $success,
        ]);
    }

    private function validateReCaptcha(string $token)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . $this->getParameter('re_captcha_secret') .
            "&response=" . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = json_decode($response);
        if ($httpCode !== 200 || $result->success === false) {
            throw new ValidatorException('No bot requests allowed.');
        }

        curl_close($ch);
    }
}
