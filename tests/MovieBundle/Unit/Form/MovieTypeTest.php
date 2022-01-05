<?php

declare(strict_types=1);

namespace App\Tests\MovieBundle\Unit\Form;

use App\MovieBundle\Form\MovieType;
use Symfony\Component\Form\Test\TypeTestCase;

class MovieTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $formData = [
            'title' => 'The Godfather',
            'year' => 1972
        ];

        $form = $this->factory->create(MovieType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }
}
