<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Functional\Command;

use App\Entity\Region;
use App\Repository\RegionRepositoryInterface;
use App\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

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

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);

        $region = $regionRepository->find(1);

        $this->assertInstanceOf(Region::class, $region);
    }
}
