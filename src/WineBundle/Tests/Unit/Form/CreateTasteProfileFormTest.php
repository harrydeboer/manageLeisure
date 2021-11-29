<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Form\CreateTasteProfileForm;
use Symfony\Component\Form\Test\TypeTestCase;

class CreateTasteProfileFormTest extends TypeTestCase
{
    public function testSubmitModel()
    {
        $name = 'testTasteProfile';
        $formData = [
            'name' => $name,
        ];

        $tasteProfile = new TasteProfile();

        $form = $this->factory->create(CreateTasteProfileForm::class, $tasteProfile);

        $expected = new TasteProfile();
        $expected->setName($name);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $tasteProfile);
    }
}
