<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateMailUserType extends AbstractMailUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ]);
        parent::buildForm($builder, $options);
    }
}
