<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\WineBundle\Entity\Category;
use App\WineBundle\Form\CreateCategoryForm;
use Symfony\Component\Form\Test\TypeTestCase;

class CreateCategoryFormTest extends TypeTestCase
{
    public function testSubmitModel()
    {
        $name = 'testCategory';
        $formData = [
            'name' => $name,
        ];

        $category = new Category();

        $form = $this->factory->create(CreateCategoryForm::class, $category);

        $expected = new Category();
        $expected->setName($name);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $category);
    }
}
