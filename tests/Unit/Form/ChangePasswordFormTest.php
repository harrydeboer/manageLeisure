<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Entity\User;
use App\Form\ChangePasswordForm;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class ChangePasswordFormTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $newPassword = 'newNew';
        $formData = [
            'plainPassword' => $newPassword,
        ];

        $form = $this->factory->create(ChangePasswordForm::class);

        $form->submit($formData);

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
