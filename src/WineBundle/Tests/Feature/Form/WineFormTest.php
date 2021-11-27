<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Feature\Form;

use App\Tests\Feature\AuthControllerTestCase;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\WineForm;

class WineFormTest extends AuthControllerTestCase
{
    public function testSubmitModel()
    {
        $name = 'testWine';
        $year = 2020;
        $rating = 8;
        $price = 10;
        $formData = [
            'name' => $name,
            'year' => $year,
            'rating' => $rating,
            'price' => $price,
        ];

        $wine = new Wine();

        $form = $this->getContainer()->get('form.factory')->create(WineForm::class, $wine);

        $expected = new Wine();
        $expected->setName($name);
        $expected->setYear($year);
        $expected->setRating($rating);
        $expected->setPrice($price);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $wine);
    }
}
