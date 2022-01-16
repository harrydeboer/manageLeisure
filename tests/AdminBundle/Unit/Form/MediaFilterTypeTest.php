<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Unit\Form;

use App\AdminBundle\Form\MediaFilterType;
use Symfony\Component\Form\Test\TypeTestCase;

class MediaFilterTypeTest extends TypeTestCase
{
    public function testSubmitModel()
    {
        $formData = [
            'year' => date('Y'),
            'month' => date('m'),
        ];

        $form = $this->factory->create(MediaFilterType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }
}
