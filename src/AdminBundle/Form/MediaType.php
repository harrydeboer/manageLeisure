<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, [
            'attr' => [
                'class' => 'btn-primary'
            ],
        ])->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
