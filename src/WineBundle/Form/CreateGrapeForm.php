<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateGrapeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => ['red' => 'red', 'white' => 'white'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('create', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }
}
