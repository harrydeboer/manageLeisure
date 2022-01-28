<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Unit\Form;

use App\AdminBundle\Form\MediaType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MediaTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $testFilePath = __DIR__ . '/test.png';
        $file = new File($testFilePath);

        $formData = [
            'file' => $file,
        ];

        $form = $this->factory->create(MediaType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }
}
