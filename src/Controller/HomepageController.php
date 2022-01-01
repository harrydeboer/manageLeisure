<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController
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

    /**
     * @Route("/uploads/wine/labels/test/{fileName}", name="wineLabelsTest")
     * @Route("/uploads/wine/labels/{fileName}", name="wineLabels")     *
     */
    public function wineLabels(string $fileName): BinaryFileResponse
    {
        if (!is_null($this->getUser())) {
            $id = (int) explode('.', $fileName)[0];

            $wine = $this->wineRepository->findOneBy(['id' => $id, 'user' => $this->getUser()->getId()]);

            if (is_null($wine)) {
                throw new NotFoundHttpException('This file does not exist or does not belong to you.');
            }

            $fileName = $this->kernel->getProjectDir() . '/' . $wine->getLabelPath($this->kernel->getEnvironment());
            $fp = fopen($fileName, 'rb');
            $mimeType = mime_content_type($fp);

            $headers = [
                "Content-Type:" => $mimeType,
                'Content-Length' => filesize($fileName),
            ];

            return new BinaryFileResponse($fileName, 200, $headers);
        }

        throw new NotFoundHttpException('This file does not exist or does not belong to you.');
    }

    public function uploads(): void
    {
        throw new NotFoundHttpException('This file does not exist or does not belong to you.');
    }

    public function catchAll(): void
    {
        throw new NotFoundHttpException('The page has not been found.');
    }

    /**
     * @return ?User
     */
    protected function getUser(): ?UserInterface
    {
        return parent::getUser();
    }
}
