<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateMailUserType extends AbstractMailUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ]);
        parent::buildForm($builder, $options);
    }
}
