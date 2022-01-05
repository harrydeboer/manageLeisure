<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Form;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\WineType;

class WineTypeTest extends AuthWebTestCase
{
    public function testSubmitModel()
    {
        $name = 'testWine';
        $type = 'red';
        $year = 2020;
        $rating = 8;
        $price = 10;
        $formData = [
            'name' => $name,
            'type' => $type,
            'year' => $year,
            'rating' => $rating,
            'price' => $price,
        ];

        $wine = new Wine();

        $form = $this->getContainer()->get('form.factory')->create(WineType::class, $wine);

        $expected = new Wine();
        $expected->setName($name);
        $expected->setType($type);
        $expected->setYear($year);
        $expected->setRating($rating);
        $expected->setPrice($price);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $wine);
    }
}
