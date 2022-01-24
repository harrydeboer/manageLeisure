<?php

declare(strict_types=1);

namespace App\WineBundle\Type;

class WineType extends AbstractEnumType
{
    protected $name = 'wine_type';
    protected $values = array('red', 'white', 'rosé', 'orange', 'sparkling', 'dessert', 'fortified');
}
