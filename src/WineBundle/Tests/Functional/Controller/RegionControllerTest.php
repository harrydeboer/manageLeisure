<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\Factory\CountryFactory;
use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Repository\RegionRepositoryInterface;

class RegionControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $country = $this->getContainer()->get(CountryFactory::class)->create($this->user);

        $this->client->request('GET', '/wine/region');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Region');

        $crawler = $this->client->request('GET', '/wine/region/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['region[name]'] = 'test';
        $form['region[country]'] = $country->getId();

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
