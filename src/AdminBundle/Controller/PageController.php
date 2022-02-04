<?php

declare(strict_types=1);

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\AdminBundle\Form\DeletePageType;
use App\AdminBundle\Form\PageType;
use App\Controller\AuthController;
use App\Entity\Page;
use App\Repository\PageRepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class PageController extends AuthController
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private KernelInterface $kernel,
    ) {
    }

    #[Route('/page', name: 'adminPage')]
    public function view(): Response
    {
        $pages = $this->pageRepository->findAll();

        return $this->render('@AdminBundle/page/view.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/page/edit/{id}', name: 'adminPageEdit')]
    public function edit(Request $request, Page $page): Response
    {
        $formUpdate = $this->createForm(PageType::class, $page, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeletePageType::class, $page, [
            'action' => $this->generateUrl('adminPageDelete', ['id' => $page->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->pageRepository->update();

            return $this->redirectToRoute('adminPage');
        }

        $this->reIndex();

        return $this->renderForm('@AdminBundle/page/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/page/create', name: 'adminPageCreate')]
    public function new(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setAuthor($this->getUser());
            $page->setPublishedAt(time());
            $this->pageRepository->create($page);

            return $this->redirectToRoute('adminPage');
        }

        $this->reIndex();

        return $this->renderForm('@AdminBundle/page/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/page/delete/{id}', name: 'adminPageDelete')]
    public function delete(Request $request, Page $page): RedirectResponse
    {
        $form = $this->createForm(DeletePageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pageRepository->delete($page);
        }

        $this->reIndex();

        return $this->redirectToRoute('adminPage');
    }

    /**
     * @throws Exception
     */
    private function reIndex()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'fos:elastica:populate',
        ]);

        $output = new NullOutput();
        $application->run($input, $output);
    }
}
