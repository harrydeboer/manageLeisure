<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeAdminCommand extends Command
{
    protected static $defaultName = 'make:admin';

    public function __construct(
        private UserRepositoryInterface $userRepository,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    /**
     * User number one becomes administrator.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userRepository->find(1);
        $user->setRoles(['ROLE_ADMIN']);
        $this->userRepository->update();

        $output->writeln('Added ROLE_ADMIN to user number one.');


        return Command::SUCCESS;
    }
}
