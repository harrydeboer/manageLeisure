<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

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

        $this->client->xmlHttpRequest('GET', '/wine/country/get-regions/' . $country->getId());

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/wine/country/edit/' . $country->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['country[name]'] = 'Italy';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/country');

        $crawler = $this->client->request('GET', '/wine/country/edit/' . $country->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/country');
    }
}
