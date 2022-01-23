<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    /** @noinspection PhpIncludeInspection */
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    $dotenv = new Dotenv();
    $dotenv->bootEnv(dirname(__DIR__).'/.env');
    $dotenv->load(dirname(__DIR__).'/.env.local');
}
