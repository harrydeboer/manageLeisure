<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class MediaFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('year', NumberType::class, [
            'attr' => ['placeholder' => 'year'],
        ])
            ->add('month', NumberType::class, [
                'attr' => ['placeholder' => 'month'],
            ])
            ->add('show', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
