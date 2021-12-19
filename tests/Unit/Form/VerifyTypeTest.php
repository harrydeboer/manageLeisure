<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Form\VerifyType;
use Symfony\Component\Form\Test\TypeTestCase;

class VerifyTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(VerifyType::class);

        $form->submit([]);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());
    }
}
