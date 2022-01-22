<?php

declare(strict_types=1);

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\DeleteMediaType;
use App\AdminBundle\Form\MediaFilterType;
use App\AdminBundle\Form\MediaType;
use App\Controller\AuthController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AuthController
{
    public function __construct(
        private KernelInterface $kernel,
    ) {
    }

    #[Route('/media', name: 'adminMedia'),
        Route('/media/filter/{year}/{month}', name: 'adminMediaFilter')]
    public function view(string $year = null, string $month = null): Response
    {
        $form = $this->createForm(MediaFilterType::class, ['year' => $year, 'month' => $month], [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);

        $files = [];
        if (!is_null($year) && !is_null($month)) {

            $base = $this->kernel->getProjectDir() . '/public/uploads/' .
                $this->extraPath() . $year . '/' . $month . '/';
            if (is_dir($base)) {
                $filesScan = scandir($base);
                foreach ($filesScan as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $files[] = $file;
                    }
                }
            }
        }

        return $this->render('@AdminBundle/media/view.html.twig', [
            'form' => $form->createView(),
            'files' => $files,
            'year' => $year,
            'month' => $month,
        ]);
    }

    #[Route('/media/edit/{year}/{month}/{fileName}', name: 'adminMediaEdit')]
    public function edit(Request $request, string $year, string $month, string $fileName): Response
    {
        $formUpdate = $this->createForm(MediaType::class);

        $formDelete = $this->createForm(DeleteMediaType::class, null, [
            'action' => $this->generateUrl('adminMediaDelete', [
                'year' => $year, 'month' => $month, 'fileName' => $fileName]),
            'method' => 'POST',
        ]);
        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {

            $file = $formUpdate->get('file')->getData();
            $base = $this->kernel->getProjectDir() . '/public/uploads/' . $this->extraPath();
            $file->move($base . $year . '/' . $month . '/', $fileName);

            return $this->redirectToRoute('adminMedia');
        }

        return $this->renderForm('@AdminBundle/media/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    #[Route('/media/create', name: 'adminMediaCreate')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(MediaType::class, null, [
            'action' => $this->generateUrl('adminMediaCreate'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();

            $base = $this->kernel->getProjectDir() . '/public/uploads/' . $this->extraPath();

            if (!is_dir($base . date('Y'))) {
                mkdir($base . date('Y'));
            }

            if (!is_dir($base . date('Y') . '/' . date('m'))) {
                mkdir($base . date('Y') . '/' . date('m'));
            }

            $file->move($base . date('Y') . '/' . date('m') . '/', $file->getClientOriginalName());

            return $this->redirectToRoute('adminMedia');
        }

        return $this->renderForm('@AdminBundle/media/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/media/delete/{year}/{month}/{fileName}', name: 'adminMediaDelete')]
    public function delete(Request $request, string $year, string $month, string $fileName): RedirectResponse
    {
        $form = $this->createForm(DeleteMediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            unlink($this->kernel->getProjectDir() .
                '/public/uploads/' . $this->extraPath() . $year . '/' . $month . '/' . $fileName);
        }

        return $this->redirectToRoute('adminMedia');
    }

    private function extraPath(): string
    {
        $extraPath = '';
        if ($this->kernel->getEnvironment() === 'test') {
            $extraPath = 'test/';
        }

        return $extraPath;
    }
}
