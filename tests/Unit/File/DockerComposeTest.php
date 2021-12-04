<?php

declare(strict_types=1);

namespace App\Tests\Unit\File;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Dotenv\Dotenv;

class DockerComposeTest extends TestCase
{
    public function testVersionsWordpressTestSuiteMatch()
    {
        $projectDir = dirname(__DIR__, 3);

        $yamlArray = Yaml::parse(file_get_contents($projectDir . '/docker-compose.yml'));
        $imageArray = explode(':', $yamlArray['services']['database']['image']);

        $dotEnv = new Dotenv('dev');
        $dotEnv->bootEnv($projectDir . '/.env');
        $databaseUrlArray = explode('=', $_ENV['DATABASE_URL']);

        $this->assertEquals($imageArray[1], $databaseUrlArray[1]);
    }
}
