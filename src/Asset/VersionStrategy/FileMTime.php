<?php

declare(strict_types=1);

namespace App\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class FileMTime implements VersionStrategyInterface
{
    public function getVersion(string $path): string
    {

        return (string) filemtime(dirname(__DIR__, 3) . '/public/' . $path);
    }

    public function applyVersion(string $path): string
    {
        $version = $this->getVersion($path);

        if ('' === $version) {
            return $path;
        }

        return $path . '?' . $version;
    }
}
