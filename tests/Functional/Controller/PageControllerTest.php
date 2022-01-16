<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Factory\PageFactory;
use App\Tests\Functional\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testUploads(): void
    {
        $page = $this->getContainer()->get(PageFactory::class)->create();

        $this->client->request('GET', $page->getSlug());

        $this->assertResponseIsSuccessful();
        
        $this->client->request('GET', '/doesNotExist/');

        $this->assertResponseStatusCodeSame(404);
    }
}
