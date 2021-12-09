<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Repository\GrapeRepositoryInterface;

class GrapeControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine/grape');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Grape');

        $crawler = $this->client->request('GET', '/wine/grape/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['grape[name]'] = 'test';
        $form['grape[type]'] = 'red';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');

        $grapeRepository = $this->getContainer()->get(GrapeRepositoryInterface::class);

        $grape = $grapeRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/grape/edit/' . $grape->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['grape[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');

        $crawler = $this->client->request('GET', '/wine/grape/edit/' . $grape->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/grape');
    }
}
