<?php

declare(strict_types=1);

namespace App\MovieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'attr' => [
                'placeholder' => 'title',
                'class' => 'form-control',
                ],
        ])
            ->add('year', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'year',
                    'class' => 'form-control',
                    ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
