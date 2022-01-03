<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\WineBundle\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;

class CountryControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine/country');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Countries');

        $crawler = $this->client->request('GET', '/wine/country/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['country[name]'] = 'France';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/country');

        $countryRepository = $this->getContainer()->get(CountryRepositoryInterface::class);

        $country = $countryRepository->findOneBy(['name' => 'France']);
        $id = $country->getId();

        $this->client->xmlHttpRequest('GET', '/wine/country/get-regions/' . $id);

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/wine/country/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedName = 'Italy';
        $form['country[name]'] = $updatedName;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/country');

        $country = $countryRepository->find($id);

        $this->assertEquals($updatedName, $country->getName());

        $crawler = $this->client->request('GET', '/wine/country/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/country');

        $countryRepository = $this->getContainer()->get(CountryRepositoryInterface::class);

        $this->assertNull($countryRepository->find($id));
    }
}
