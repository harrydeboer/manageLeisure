<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import';

    public function __construct(
            private EntityManagerInterface $em,
            string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sqlFilesPath = dirname(__DIR__) . '/WineBundle/Data/sql-files';
        $labelsPath = dirname(__DIR__) . '/WineBundle/Data/labels';
        $publicLabelsPath = dirname(__DIR__,2) . '/public/img/labels';

        $files = scandir($sqlFilesPath);
        foreach($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $this->em->getConnection()->executeQuery(file_get_contents($sqlFilesPath . '/' . $file));
            }
        }

        $output->writeln('SQL files loaded.');

        $files = scandir($labelsPath);
        foreach($files as $file) {
            if ($file !== '.' && $file !== '..') {
                copy($labelsPath . '/' . $file, $publicLabelsPath . '/' . $file);
            }
        }
        $output->writeln('Labels moved to public/img/labels.');

        return Command::SUCCESS;
    }
}
