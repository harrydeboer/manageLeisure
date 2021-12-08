<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This form is extended by create and update wine forms.
 * It extends the filter form because filtering on the wine homepage has grapes, taste profiles and regions also.
 */
class WineForm extends WineFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('year', IntegerType::class, [
                'attr' => ['min' => 1000, 'max' => 9999, 'placeholder' => 'year', 'class' => 'form-control'],
            ])
            ->add('price', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.01', 'min' => '0', 'class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('rating', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.1', 'min' => '1.0', 'max' => '10.0', 'class' => 'form-control'],
            ])
            ->add('review', TextareaType::class, [
                'required' => false,
                'attr' => ['step' => '0.1', 'min' => '1.0', 'max' => '10.0', 'class' => 'form-control'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
