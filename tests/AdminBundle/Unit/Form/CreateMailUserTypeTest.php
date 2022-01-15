<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Unit\Form;

use App\AdminBundle\Entity\MailUser;
use App\AdminBundle\Form\CreateMailUserType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class CreateMailUserTypeTest extends TypeTestCase
{
    public function testSubmitModel(): void
    {
        $domain = 'test.com';
        $email = 'test@test.com';
        $password = 'testTest';
        $formData = [
            'domain' => $domain,
            'email' => $email,
            'password' => $password,
        ];

        $mailUser = new MailUser();

        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(CreateMailUserType::class, $mailUser);

        $expected = new MailUser();
        $expected->setDomain($domain);
        $expected->setEmail($email);
        $expected->setPassword($password);
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $mailUser);
    }

    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
