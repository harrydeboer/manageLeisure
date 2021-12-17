<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorController extends AbstractController
{
    public function __construct(
        private KernelInterface $kernel,
        private Environment $environment,
    ) {
    }

    public function show(Throwable $exception): Response
    {
        /**
         * When the exception has a status code the matching status code page is rendered.
         */
        if (method_exists($exception, 'getStatusCode')) {
            $statusCodeString = (string) $exception->getStatusCode();

            if ($statusCodeString === '403' && !$this->getUser()->isVerified()) {
                return $this->redirectToRoute('verifyAgain', ['send' => 0]);
            }

            $templatePath = 'error/' . $statusCodeString . '.html.twig';
            if ($this->environment->getLoader()->exists($templatePath)) {
                return $this->render($templatePath, [
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        if ($this->kernel->getEnvironment() === 'dev') {
            return $this->render('error/500.html.twig', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        return $this->render('error/500.html.twig', [
            'message' => 'Something went wrong.'
        ]);
    }
}
