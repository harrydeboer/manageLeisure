<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Controller;

use App\Tests\Feature\AuthWebTestCase;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;

class TasteProfileControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/wine/taste-profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Taste Profile');

        $crawler = $this->client->request('GET', '/wine/taste-profile/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['taste_profile_form[name]'] = 'test';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');

        $tasteProfileRepository = $this->getContainer()->get(TasteProfileRepositoryInterface::class);

        $tasteProfile = $tasteProfileRepository->findOneBy(['name' => 'test']);

        $this->client->request('GET', '/wine/taste-profile/single/' . $tasteProfile->getId());

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/wine/taste-profile/edit/' . $tasteProfile->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['taste_profile_form[name]'] = 'test2';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');

        $crawler = $this->client->request('GET', '/wine/taste-profile/edit/' . $tasteProfile->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');
    }
}
