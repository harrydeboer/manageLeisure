<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Tests\Factory\WineFactory;
use Symfony\Component\HttpKernel\KernelInterface;
use Exception;

class UploadsControllerTest extends AuthWebTestCase
{
    /**
     * @throws Exception
     */
    public function testWineLabels(): void
    {
        $kernel = $this->getContainer()->get(KernelInterface::class);

        $wine = $this->getContainer()->get(WineFactory::class)->create(['user' => $this->user]);

        copy(__DIR__ . '/test.png', $kernel->getProjectDir() . '/uploads/wine/labels/test/1.png');

        $this->client->request('GET', '/uploads/wine/labels/test/1.png');

        $this->assertResponseIsSuccessful();

        $wine->unlinkLabel($kernel->getEnvironment(), $kernel->getProjectDir());
    }

    public function testUploads(): void
    {
        $this->client->request('GET', '/uploads/');

        $this->assertResponseStatusCodeSame(404);
    }
}
