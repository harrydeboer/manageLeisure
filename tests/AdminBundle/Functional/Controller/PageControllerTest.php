<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Functional\Controller;

use App\Repository\PageRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;

class PageControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);
        $this->user->setRoles(['ROLE_ADMIN']);
        $userRepository->update();
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/admin/page');

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/page/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['page[title]'] = 'testTitle';
        $form['page[slug]'] = 'testSlug';
        $form['page[summary]'] = 'testSum';
        $form['page[content]'] = 'testContent';

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/page');

        $pageRepository = $this->getContainer()->get(PageRepositoryInterface::class);

        $page = $pageRepository->findOneBy(['slug' => 'testSlug']);
        $id = $page->getId();

        $crawler = $this->client->request('GET', '/admin/page/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedTitle = 'testTitle2';
        $form['page[title]'] = $updatedTitle;

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/page');

        $page = $pageRepository->findOneBy(['slug' => 'testSlug']);

        $this->assertEquals($updatedTitle, $page->getTitle());

        $crawler = $this->client->request('GET', '/admin/page/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/page');

        $pageRepository = $this->getContainer()->get(PageRepositoryInterface::class);

        $this->assertNull($pageRepository->find($id));
    }
}
