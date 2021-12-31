<?php

declare(strict_types=1);

namespace App\WineBundle\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ImportCommand extends Command
{
    protected static $defaultName = 'wine:import';

    public function __construct(
        private KernelInterface $kernel,
        private EntityManagerInterface $em,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = $this->kernel->getProjectDir();
        $sqlFilesPath = $projectDir . '/src/WineBundle/Data/sql-files';
        $labelsPath = $projectDir . '/src/WineBundle/Data/labels';
        $uploadsLabelsPath = $projectDir . '/uploads/wine/labels';
        if ($this->kernel->getEnvironment() === 'test') {
            $uploadsLabelsPath .= '/test';
        }

        $files = scandir($sqlFilesPath);
        foreach($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $this->em->getConnection()->executeQuery(file_get_contents($sqlFilesPath . '/' . $file));
            }
        }

        $output->writeln('SQL files loaded.');

        $files = scandir($labelsPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                copy($labelsPath . '/' . $file, $uploadsLabelsPath . '/' . $file);
            }
        }
        $output->writeln('Labels moved to uploads/wine/labels.');

        return Command::SUCCESS;
    }
}
