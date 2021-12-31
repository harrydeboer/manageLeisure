<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Command;

use App\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class ImportCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('wine:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('SQL files loaded.', $output);
        $this->assertStringContainsString('Labels moved to uploads/wine/labels.', $output);

        $kernel = $this->getContainer()->get(KernelInterface::class);

        $projectDir = $kernel->getProjectDir();
        $labelsPath = $projectDir . '/uploads/wine/labels/test';

        $files = scandir($labelsPath);
        foreach($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== '.gitkeep') {
                unlink($labelsPath . '/' . $file);
            }
        }
    }
}
