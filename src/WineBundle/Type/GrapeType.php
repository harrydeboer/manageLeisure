<?php

declare(strict_types=1);

namespace App\WineBundle\Type;

class GrapeType extends AbstractEnumType
{
    protected $name = 'grape_type';
    protected $values = array('red', 'white', 'rosé');
}
