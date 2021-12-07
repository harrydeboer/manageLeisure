<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Entity\Country;
use App\Form\CountryForm;
use Symfony\Component\Form\Test\TypeTestCase;

class CountryFormTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $name = 'France';
        $code = 'FR';
        $formData = [
            'name' => $name,
            'code' => $code,
        ];

        $country = new Country();

        $form = $this->factory->create(CountryForm::class, $country);

        $expected = new Country();
        $expected->setName($name);
        $expected->setCode($code);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $country);
    }
}