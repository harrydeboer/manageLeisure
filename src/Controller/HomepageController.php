<?php

declare(strict_types=1);

namespace App\Controller;

use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AuthController
{
    public function __construct(
        private KernelInterface $kernel,
        private WineRepositoryInterface $wineRepository,
    ) {
    }

    /**
     * @Route("/", name="homepage")
     */
    public function view(): Response
    {
        return $this->render('homepage/view.html.twig');
    }

    public function uploads(Request $request): void
    {
        $fileUrl = $request->query->get('file');

        if (!is_null($fileUrl) && str_contains($fileUrl, 'labels')) {
            $fileUrlArray = explode('/', $fileUrl);
            $id = (int)explode('.', $fileUrlArray[2])[0];

            $wine = $this->wineRepository->findOneBy(['id' => $id, 'user' => $this->getUser()->getId()]);

            if (is_null($wine)) {
                throw new NotFoundHttpException('This label does not exist or does not belong to you.');
            }

            $fileName = $this->kernel->getProjectDir() . '/public/uploads/' . $fileUrl;
            $fp = fopen($fileName, 'rb');
            $mimeType = mime_content_type($fp);
            header("Content-Type: " . $mimeType);
            header("Content-Length: " . filesize($fileName));
            fpassthru($fp);
        }

        throw new NotFoundHttpException('The file has not been found.');
    }

    public function catchAll(): void
    {
        throw new NotFoundHttpException('The page has not been found.');
    }
}
