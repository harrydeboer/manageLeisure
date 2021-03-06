<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Functional\Controller;

use App\Tests\Functional\AuthAdminWebTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MediaControllerTest extends AuthAdminWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/admin/media');

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/media/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $testFilePath = __DIR__ . '/test.png';
        $form['media[file]'] = new File($testFilePath);

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/media');

        $yearString = date('Y');
        $monthString = date('m');
        if (strlen($monthString) === 1) {
            $monthString = '0' . $monthString;
        }

        $crawler = $this->client->request('GET',
            '/admin/media/edit/' . $yearString . '/' . $monthString . '/test.png');

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['media[file]'] = new File($testFilePath);

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/media');

        $crawler = $this->client->request('GET',
            '/admin/media/edit/' . $yearString . '/' . $monthString . '/test.png');

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/media');

        $this->client->request('GET',
            '/admin/media/edit/' . $yearString . '/' . $monthString . '/test.png');
    }
}
