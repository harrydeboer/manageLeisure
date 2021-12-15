<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\Country;
use App\WineBundle\Form\CountryType;
use Symfony\Component\Form\Test\TypeTestCase;

class CountryTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $name = 'France';
        $formData = [
            'name' => $name,
        ];

        $country = new Country();

        $form = $this->factory->create(CountryType::class, $country);

        $expected = new Country();
        $expected->setName($name);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $country);
    }
}