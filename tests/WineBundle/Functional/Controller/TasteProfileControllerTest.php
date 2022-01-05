<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
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

        $form['taste_profile[name]'] = 'test';

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');

        $tasteProfileRepository = $this->getContainer()->get(TasteProfileRepositoryInterface::class);

        $tasteProfile = $tasteProfileRepository->findOneBy(['name' => 'test']);
        $id = $tasteProfile->getId();

        $this->client->request('GET', '/wine/taste-profile/single/' . $id);

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/wine/taste-profile/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedName = 'test2';
        $form['taste_profile[name]'] = $updatedName;

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');

        $tasteProfile = $tasteProfileRepository->find($id);

        $this->assertEquals($updatedName, $tasteProfile->getName());

        $crawler = $this->client->request('GET', '/wine/taste-profile/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/wine/taste-profile');

        $tasteProfileRepository = $this->getContainer()->get(TasteProfileRepositoryInterface::class);

        $this->assertNull($tasteProfileRepository->find($id));
    }
}
