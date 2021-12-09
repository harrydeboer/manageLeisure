<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class RegistrationTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $name = 'testUser';
        $email = 'test@test.nl';
        $formData = [
            'name' => $name,
            'email' => $email,
            'plainPassword' => 'plainPassword',
        ];

        $user = new User();

        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(RegistrationType::class, $user);

        $expected = new User();
        $expected->setName($name);
        $expected->setEmail($email);
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $user);
    }

    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
