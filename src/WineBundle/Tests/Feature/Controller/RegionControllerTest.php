<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\Feature\AuthWebTestCase;
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

        $form['country_form[name]'] = 'France';
        $form['country_form[code]'] = 'FR';

        $this->client->submit($form);

        $this->assertResponseRedirects('/country');

        $this->client->request('GET', '/wine/region');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Region');

        $crawler = $this->client->request('GET', '/wine/region/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['region_form[name]'] = 'test';
        $form['region_form[country]'] = 1;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $regionRepository = $this->getContainer()->get(RegionRepositoryInterface::class);

        $region = $regionRepository->findOneBy(['name' => 'test']);

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $region->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['region_form[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $region->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');
    }
}
