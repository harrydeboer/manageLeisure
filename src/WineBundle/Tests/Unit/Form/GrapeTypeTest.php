<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\Grape;
use App\WineBundle\Form\GrapeType;
use Symfony\Component\Form\Test\TypeTestCase;

class GrapeTypeTest extends TypeTestCase
{
    public function testSubmitModel()
    {
        $name = 'testGrape';
        $type = 'red';
        $formData = [
            'name' => $name,
            'type' => $type,
        ];

        $grape = new Grape();

        $form = $this->factory->create(GrapeType::class, $grape);

        $expected = new Grape();
        $expected->setName($name);
        $expected->setType($type);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $grape);
    }
}