<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorController extends AbstractController
{
    public function __construct(
        private Environment $environment,
    ) {
    }

    public function show(Throwable $exception): ?Response
    {
        /**
         * When the exception has a status code the matching status code page is rendered.
         */
        if (method_exists($exception, 'getStatusCode')) {
            $statusCodeString = (string) $exception->getStatusCode();

            if ($statusCodeString === '403' && !$this->getUser()->isVerified()) {
                return $this->redirectToRoute('sendVerificationEmailAgain');
            }

            $templatePath = 'error/' . $statusCodeString . '.html.twig';
            if ($this->environment->getLoader()->exists($templatePath)) {
                return $this->render($templatePath, [
                    'message' => $exception->getMessage(),
                ], new Response('', (int) $statusCodeString));
            }
        }

        return $this->render('error/500.html.twig', [
            'message' => 'Something went wrong.'
        ], new Response('', 500));
    }

    /**
     * @return ?User
     */
    protected function getUser(): ?UserInterface
    {
        return parent::getUser();
    }
}
