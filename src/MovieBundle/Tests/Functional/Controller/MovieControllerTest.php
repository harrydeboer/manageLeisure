<?php

declare(strict_types=1);

namespace App\MovieBundle\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Factory\GrapeFactory;
use App\WineBundle\Factory\RegionFactory;
use App\WineBundle\Repository\WineRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

class MovieControllerTest extends AuthWebTestCase
{
    public function testMovie(): void
    {
        $crawler = $this->client->request('GET', '/movie');

        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Submit');

        $form = $buttonCrawlerNode->form();

        $form['movie[title]'] = 'The Godfather';
        $form['movie[year]'] = 1972;

        $this->client->submit($form);

        $this->assertResponseIsSuccessful();
    }
}
