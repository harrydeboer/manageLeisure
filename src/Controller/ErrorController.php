<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Throwable;

class ErrorController extends Controller
{
    public function show(Throwable $exception, DebugLoggerInterface $logger = null): Response
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $this->render('error/' . $exception->getStatusCode() . '.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }

        return $this->render('error/500.html.twig', [
            'message' => $exception->getMessage()
        ]);
    }
}
