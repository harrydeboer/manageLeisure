<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageNotFoundController extends AbstractController
{
    public function pageNotFound(): void
    {
        throw new NotFoundHttpException('The page has not been found.');
    }
}
