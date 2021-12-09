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

class WineControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $country = new Country();
        $country->setUser($this->user);
        $country->setName('France');

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);
        $countryRepository->create($country);

        $region = new Region();
        $region->setUser($this->user);
        $region->setCountry($country);
        $region->setName('Bordeaux');

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);
        $regionRepository->create($region);

        $this->client->request('GET', '/wine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');

        $wine = new Wine();
        $wine->setUser($this->user);
        $wine->setName('test');
        $wine->setCreatedAt(time());
        $wine->setPrice(10);
        $wine->setRating(8);
        $wine->setYear(2000);
        $wine->setLabelExtension('png');
        $wine->setCountry($country);
        $wine->setRegion($region);

        $wineRepository = $this->getContainer()->get(WineRepositoryInterface::class);

        $wineRepository->create($wine);

        $this->client->request('GET', '/wine?tasteProfile=&year=&sort=createdAt_DESC&show=');

        $this->assertResponseIsSuccessful();

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
