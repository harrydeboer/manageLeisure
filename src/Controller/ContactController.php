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
            $email = (new Email())
                ->from(new Address('postmaster@manageleisure.com', $form->get('name')->getData()))
                ->replyTo($form->get('email')->getData())
                ->to('info@manageleisure.com')
                ->subject($form->get('subject')->getData())
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
            'success' => $success,
        ]);
    }
}
