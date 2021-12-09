<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;

class CountryControllerTest extends AuthWebTestCase
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

        $this->client->submit($form);

        $this->assertResponseRedirects('/country');

        $countryRepository = $this->getContainer()->get(CountryRepositoryInterface::class);

        $country = $countryRepository->findOneBy(['name' => 'France']);

        $crawler = $this->client->request('GET', '/country/edit/' . $country->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['country_form[name]'] = 'Italy';

        $this->client->submit($form);

        $this->assertResponseRedirects('/country');

        $crawler = $this->client->request('GET', '/country/edit/' . $country->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/country');
    }
}
