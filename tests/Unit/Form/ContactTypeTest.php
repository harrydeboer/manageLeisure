<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Form\ContactType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ContactTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $formData = [
            'name' => 'John',
            'subject' => 'Test',
            'email' => 'test@test.com',
            'message' => 'message',
        ];

        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ContactType::class);

        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());
    }

    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
