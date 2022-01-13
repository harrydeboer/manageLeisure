<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Tests\Functional\KernelTestCase;
use App\WineBundle\Entity\Country;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Entity\Region;
use App\WineBundle\Entity\Subregion;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use App\WineBundle\Repository\SubregionRepositoryInterface;
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

        $subregionRepository = static::getContainer()->get(SubregionRepositoryInterface::class);
        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        $subregion = $subregionRepository->find(1);
        $grape = $grapeRepository->find(1);

        $this->assertInstanceOf(Country::class, $subregion->getRegion()->getCountry());
        $this->assertInstanceOf(Region::class, $subregion->getRegion());
        $this->assertInstanceOf(Subregion::class, $subregion);
        $this->assertInstanceOf(Grape::class, $grape);
    }
}
