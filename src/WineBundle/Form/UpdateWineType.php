<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateWineType extends WineType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('label', FileType::class, [
            'attr' => [
                'accept' => 'image/*',
                'class' => 'form-control'
            ],
            'required' => false,
        ]);
        parent::buildForm($builder, $options);
    }
}
