<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function view(): Response
    {
        return $this->render('homepage/view.html.twig');
    }
}
