<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

trait MigrationsTrait
{
    protected ?EntityManager $entityManager;

    protected function migrate(): void
    {
        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($metaData);
    }

    protected function drop(): void
    {
        $db = $this->entityManager->getConnection()->getDatabase();
        $this->entityManager->getConnection()->executeQuery('DROP DATABASE ' . $db);
        $this->entityManager->getConnection()->executeQuery('CREATE DATABASE ' . $db);

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
