<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Command;

use App\Entity\Country;
use App\Entity\Region;
use App\Repository\RegionRepositoryInterface;
use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;
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
        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        $region = $regionRepository->find(1);
        $grape = $grapeRepository->find(1);

        $this->assertInstanceOf(Region::class, $region);
        $this->assertInstanceOf(Country::class, $region->getCountry());
        $this->assertInstanceOf(Grape::class, $grape);
    }
}
