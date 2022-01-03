<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Tests\Factory\GrapeFactory;
use App\WineBundle\Tests\Factory\RegionFactory;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

class WineControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $region = $this->getContainer()->get(RegionFactory::class)->create(['user' => $this->user]);
        $this->getContainer()->get(GrapeFactory::class)->create(['user' => $this->user]);
        $this->getContainer()->get(GrapeFactory::class)->create(['user' => $this->user]);

        $this->client->request('GET', '/wine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Home');

        $crawler = $this->client->request('GET', '/wine/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $testLabelPath = __DIR__ . '/test.png';
        $form['wine[label]'] = new File($testLabelPath);
        $form['wine[name]'] = 'test';
        $form['wine[type]'] = 'red';
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $form['wine[grapes]'][0]->tick();
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $form['wine[grapes]'][1]->tick();
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
        $id = $wine->getId();

        $this->client->request('GET', '/wine/single/' . $id);

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/wine/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedName = 'test2';
        $form['update_wine[name]'] = $updatedName;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $wine = $wineRepository->find($id);

        $this->assertEquals($updatedName, $wine->getName());

        $crawler = $this->client->request('GET', '/wine/edit/' . $wine->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine');

        $wineRepository = $this->getContainer()->get(WineRepositoryInterface::class);

        $this->assertNull($wineRepository->find($id));
    }
}
