<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use InvalidArgumentException;

abstract class AbstractFactory
{
    protected function setParams(array $params, object $entity): void
    {
        foreach ($params as $key => $param) {
            if ($key === 'id') {
                throw new InvalidArgumentException(
                    'The create method of this factory is not allowed to set the id.');
            }
            $method = 'set' . ucfirst($key);
            if (!method_exists($entity, $method)) {
                throw new InvalidArgumentException('The setter ' . $method . ' does not exist in ' .
                    $entity::class . ' for property ' . $key . ' .');
            }
            $entity->$method($param);
        }
    }
}
