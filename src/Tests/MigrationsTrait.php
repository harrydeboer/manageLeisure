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

        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($metaData);
    }

    protected function drop(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metaData);
    }
}
