<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\AuthControllerTestCase;
use App\WineBundle\Repository\CategoryRepositoryInterface;

class CategoryControllerTest extends AuthControllerTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine/category');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Category');

        $crawler = $this->client->request('GET', '/wine/category/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['create_category_form[name]'] = 'test';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/category');

        $categoryRepository = $this->getContainer()->get(CategoryRepositoryInterface::class);

        $category = $categoryRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/category/edit/' . $category->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['update_category_form[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/category');

        $crawler = $this->client->request('GET', '/wine/category/edit/' . $category->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/category');
    }
}
