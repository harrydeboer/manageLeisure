<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Form\TasteProfileType;
use Symfony\Component\Form\Test\TypeTestCase;

class TasteProfileTypeTest extends TypeTestCase
{
    public function testSubmitModel()
    {
        $name = 'testTasteProfile';
        $formData = [
            'name' => $name,
        ];

        $tasteProfile = new TasteProfile();

        $form = $this->factory->create(TasteProfileType::class, $tasteProfile);

        $expected = new TasteProfile();
        $expected->setName($name);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $tasteProfile);
    }
}
