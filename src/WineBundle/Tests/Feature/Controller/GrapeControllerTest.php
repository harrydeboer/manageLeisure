<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\Feature\AuthControllerTestCase;
use App\WineBundle\Repository\GrapeRepositoryInterface;

class GrapeControllerTest extends AuthControllerTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine/grape');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Grape');

        $crawler = $this->client->request('GET', '/wine/grape/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['create_grape_form[name]'] = 'test';
        $form['create_grape_form[type]'] = 'red';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');

        $grapeRepository = $this->getContainer()->get(GrapeRepositoryInterface::class);

        $grape = $grapeRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/grape/edit/' . $grape->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['update_grape_form[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');

        $crawler = $this->client->request('GET', '/wine/grape/edit/' . $grape->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');
    }
}
