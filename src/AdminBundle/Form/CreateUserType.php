<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use App\Form\RegistrationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUserType extends RegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('roles', ChoiceType::class, [
            'placeholder' => 'select role',
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'ROLE_ADMIN' => 'ROLE_ADMIN',
            ],
            'attr' => ['class' => 'form-control'],
        ])->add('isVerified', ChoiceType::class, [
            'placeholder' => 'select verified',
            'choices' => [
                'yes' => '1',
                'no' => '0',
            ],
        ]);
        parent::buildForm($builder, $options);
    }
}
