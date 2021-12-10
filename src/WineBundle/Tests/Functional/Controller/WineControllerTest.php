<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\Entity\Country;
use App\Repository\CountryRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Entity\Region;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\RegionRepositoryInterface;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

class WineControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $region = $this->createRegion($this->user);

        $this->client->request('GET', '/wine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');

        $crawler = $this->client->request('GET', '/wine/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['wine[label]'] = new File(dirname(__DIR__) . '/test.png');
        $form['wine[name]'] = 'test';
        $form['wine[year]'] = 2000;
        $form['wine[rating]'] = 7;
        $form['wine[price]'] = 10;
        $form['wine[country]'] = $region->getCountry()->getId();

        /**
         * The create page has no regions but when a country is selected the regions are retrieved
         * by ajax and javascript. The javascript does not work in a php browser so a region is handpicked.
         */
        $values = $form->getPhpValues();
        $values['wine']['region'] = $region->getId();
        $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

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

        $form['update_wine[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $crawler = $this->client->request('GET', '/wine/edit/' . $wine->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');
    }
}
