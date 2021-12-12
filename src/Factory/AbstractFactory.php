<?php

declare(strict_types=1);

namespace App\Factory;

abstract class AbstractFactory
{
    protected function setParams(array $params, object $entity)
    {
        foreach ($params as $key => $param) {
            $key = ucfirst($key);
            $method = 'set' . $key;
            $entity->$method($param);
        }
    }
}
