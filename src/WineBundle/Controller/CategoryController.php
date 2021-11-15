<?php

declare(strict_types=1);

namespace App\WineBundle\Controller;

use App\Controller\Controller;
use App\WineBundle\Entity\Category;
use App\WineBundle\Form\CreateCategoryForm;
use App\WineBundle\Form\DeleteCategoryForm;
use App\WineBundle\Form\UpdateCategoryForm;
use App\WineBundle\Repository\CategoryRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    /**
     * @Route("/wine/category", name="wineCategory")
     */
    public function view(): Response
    {
        $categories = $this->getCurrentUser()->getCategories();

        return $this->render('@WineBundle/category/view.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/wine/category/edit/{id}", name="wineCategoryEdit")
     */
    public function edit(Request $request, Category $category): Response
    {
        $formUpdate = $this->createForm(UpdateCategoryForm::class, $category, [
            'method' => 'POST',
        ]);

        $formDelete = $this->createForm(DeleteCategoryForm::class, $category, [
            'action' => $this->generateUrl('wineCategoryDelete', ['id' => $category->getId()]),
            'method' => 'POST',
        ]);

        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->checkUser($category->getUser());
            $this->categoryRepository->update();

            return $this->redirectToRoute('wineCategory');
        }

        return $this->renderForm('@WineBundle/category/edit/view.html.twig', [
            'formUpdate' => $formUpdate,
            'formDelete' => $formDelete,
        ]);
    }

    /**
     * @Route("/wine/category/create", name="wineCategoryCreate")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CreateCategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($this->getCurrentUser());
            $this->categoryRepository->create($category);

            return $this->redirectToRoute('wineCategory');
        }

        return $this->renderForm('@WineBundle/category/new/view.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/wine/category/delete/{id}", name="wineCategoryDelete")
     */
    public function delete(Request $request, Category $category): RedirectResponse
    {
        $form = $this->createForm(DeleteCategoryForm::class);
        $form->handleRequest($request);
        $this->checkUser($category->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->delete($category);
        }

        return $this->redirectToRoute('wineCategory');
    }
}
