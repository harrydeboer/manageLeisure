<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\WineBundle\Tests\Factory\CountryFactory;
use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Repository\RegionRepositoryInterface;

class RegionControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $country = $this->getContainer()->get(CountryFactory::class)->create(['user' => $this->user]);

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
        $id = $region->getId();

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedName = 'test2';
        $form['region[name]'] = $updatedName;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $region = $regionRepository->find($id);

        $this->assertEquals($updatedName, $region->getName());

        $crawler = $this->client->request('GET', '/wine/region/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/region');

        $regionRepository = $this->getContainer()->get(RegionRepositoryInterface::class);

        $this->assertNull($regionRepository->find($id));
    }
}
