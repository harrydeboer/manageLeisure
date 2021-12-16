<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function view(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        $success = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from('postmaster@manageleisure.com')
                ->replyTo($form->get('email')->getData())
                ->to('info@manageleisure.com')
                ->subject($form->get('subject')->getData())
                ->text($form->get('message')->getData())
                ->html('<p>See Twig integration for better HTML integration!</p>');
            $success = true;

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $success = false;
            }
        }

        return $this->render('contact/view.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }
}
