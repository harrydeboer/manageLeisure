<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('SQL files loaded.', $output);
        $this->assertStringContainsString('Labels moved to public/img/labels.', $output);
    }
}
