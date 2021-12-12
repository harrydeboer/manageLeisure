<?php

declare(strict_types=1);

namespace App\Tests\Unit\File;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class EnvTest extends TestCase
{
    public function testEnvLaravel()
    {
        $projectDir = dirname(__DIR__, 3);

        $_ENV = [];

	    $dotEnv = new Dotenv('dev');
        $dotEnv->load($projectDir . '/.env.local');

	    $envNames = $_ENV;

        $_ENV = [];

        $dotEnv = new Dotenv('dev');
        $dotEnv->load($projectDir . '/.env.local.example');

        $envExampleNames = $_ENV;

        $this->assertSameSize($envNames, $envExampleNames);

        foreach ($envExampleNames as $key => $value) {
            $value = rtrim($value);
            if ($envNames[$key] === "") {
                $this->assertTrue($value === "");
            } elseif ($key === 'DATABASE_URL') {
                continue;
            } elseif ($key === 'TEST_DATABASE') {
                $this->assertEquals($value, '_test');
            } else {
                $this->assertTrue(
                    str_starts_with($envNames[$key], $value),
                    "the first part of $envNames[$key] is not $value",
                );
            }
        }
    }
}
