<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateWineForm extends WineForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('image', FileType::class, [
            'attr' => [
                'accept' => 'image/*',
                'class' => 'btn-primary'
            ],
        ]);
        parent::buildForm($builder, $options);
    }
}
