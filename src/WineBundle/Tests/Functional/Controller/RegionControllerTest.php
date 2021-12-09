<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Repository\RegionRepositoryInterface;

class RegionControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/country');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Country');

        $crawler = $this->client->request('GET', '/country/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['country[name]'] = 'France';

        $this->client->submit($form);

        $this->assertResponseRedirects('/country');

        $this->client->request('GET', '/wine/region');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Region');

        $crawler = $this->client->request('GET', '/wine/region/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['region[name]'] = 'test';
        $form['region[country]'] = 1;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $regionRepository = $this->getContainer()->get(RegionRepositoryInterface::class);

        $region = $regionRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $region->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['region[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $region->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');
    }
}
