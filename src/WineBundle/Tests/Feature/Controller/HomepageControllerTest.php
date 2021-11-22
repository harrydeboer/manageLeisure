<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\AuthControllerTestCase;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

class HomepageControllerTest extends AuthControllerTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');

        $crawler = $this->client->request('GET', '/wine/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $kernel = $this->getContainer()->get(KernelInterface::class);

        $form['create_wine_form[image]'] = new File($kernel->getProjectDir() . '/src/Tests/test.png');
        $form['create_wine_form[name]'] = 'test';
        $form['create_wine_form[year]'] = 2000;
        $form['create_wine_form[rating]'] = 7;
        $form['create_wine_form[price]'] = 10;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $wineRepository = $this->getContainer()->get(WineRepositoryInterface::class);

        $wine = $wineRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/edit/' . $wine->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['update_wine_form[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $crawler = $this->client->request('GET', '/wine/edit/' . $wine->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');
    }
}
