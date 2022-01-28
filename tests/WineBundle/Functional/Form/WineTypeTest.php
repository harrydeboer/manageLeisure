<?php

declare(strict_types=1);

namespace App\Tests\WineBundle\Functional\Form;

use App\Tests\Functional\AuthWebTestCase;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Factory\SubregionFactory;
use App\WineBundle\Form\WineType;

class WineTypeTest extends AuthWebTestCase
{
    public function testSubmitModel(): void
    {
        $name = 'testWine';
        $type = 'red';
        $year = 2020;
        $rating = 8;
        $price = 10;
        $subregion = $this->getContainer()->get(SubregionFactory::class)->create();
        $formData = [
            'name' => $name,
            'type' => $type,
            'year' => $year,
            'rating' => $rating,
            'price' => $price,
            'country' => $subregion->getRegion()->getCountry()->getId(),
            'region' => $subregion->getRegion()->getId(),
            'subregion' => $subregion->getId(),
        ];

        $wine = new Wine();

        $form = $this->getContainer()->get('form.factory')->create(WineType::class, $wine, [
            'country' => $subregion->getRegion()->getCountry()->getId(),
            'region' => $subregion->getRegion()->getId(),
        ]);

        $expected = new Wine();
        $expected->setName($name);
        $expected->setType($type);
        $expected->setYear($year);
        $expected->setRating($rating);
        $expected->setPrice($price);
        $expected->setCountry($subregion->getRegion()->getCountry());
        $expected->setRegion($subregion->getRegion());
        $expected->setSubregion($subregion);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $wine);
    }
}
