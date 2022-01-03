<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

if (file_exists(dirname(__DIR__, 2).'/config/bootstrap.php')) {
    require dirname(__DIR__, 2).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    $dotenv = new Dotenv();
    $dotenv->bootEnv(dirname(__DIR__, 2).'/.env');
    $dotenv->load(dirname(__DIR__, 2).'/.env.local');
}
