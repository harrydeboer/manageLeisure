<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\Feature\AuthWebTestCase;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

class WineControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/country');

        $crawler = $this->client->request('GET', '/country/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['country_form[name]'] = 'France';

        $this->client->submit($form);

        $this->client->request('GET', '/wine/region');

        $crawler = $this->client->request('GET', '/wine/region/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['region_form[name]'] = 'Bordeaux';
        $form['region_form[country]'] = 1;

        $this->client->submit($form);

        $this->client->request('GET', '/wine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');

        $crawler = $this->client->request('GET', '/wine/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['create_wine_form[label]'] = new File(dirname(__DIR__) . '/test.png');
        $form['create_wine_form[name]'] = 'test';
        $form['create_wine_form[year]'] = 2000;
        $form['create_wine_form[rating]'] = 7;
        $form['create_wine_form[price]'] = 10;
        $form['create_wine_form[region]'] = 1;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $this->client->request('GET', '/wine?tasteProfile=&year=&sort=createdAt_DESC&show=');

        $this->assertResponseIsSuccessful();

        $wineRepository = $this->getContainer()->get(WineRepositoryInterface::class);

        $wine = $wineRepository->findOneBy(['name' => 'test']);

        $this->client->request('GET', '/wine/single/' . $wine->getId());

        $this->assertResponseIsSuccessful();

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
