<?php

declare(strict_types=1);

namespace App\Tests\MovieBundle\Functional\Controller;

use App\Tests\Functional\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testHomepageAndForm(): void
    {
        $crawler = $this->client->request('GET', '/movie');

        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Submit');

        $form = $buttonCrawlerNode->form();

        $form['movie[title]'] = 'The Godfather';

        $this->client->submit($form);

        $this->assertResponseIsSuccessful();
    }
}
