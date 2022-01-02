<?php

declare(strict_types=1);

namespace App\MovieBundle\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;

class MovieControllerTest extends AuthWebTestCase
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

        $this->client->request('GET', '/movie/get-rating', $form->getPhpFiles(), $form->getPhpFiles());

        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/movie/single-movie/tt0068646');

        $this->assertResponseIsSuccessful();
    }
}
