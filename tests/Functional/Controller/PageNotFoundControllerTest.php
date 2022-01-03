<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;

class PageNotFoundControllerTest extends AuthWebTestCase
{
    public function testUploads(): void
    {
        $this->client->request('GET', '/doesNotExist/');

        $this->assertResponseStatusCodeSame(404);
    }
}
