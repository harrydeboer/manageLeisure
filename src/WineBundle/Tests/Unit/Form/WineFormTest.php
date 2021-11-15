<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\Wine;
use App\WineBundle\Form\WineForm;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Security;

class WineFormTest extends TypeTestCase
{
    private object $security;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new WineForm($this->security);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

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

        $form = $this->factory->create(WineForm::class, $wine);

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
