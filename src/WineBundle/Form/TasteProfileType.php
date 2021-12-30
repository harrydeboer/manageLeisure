<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TasteProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control no-html-tags'],
            ])
            ->add('secondName', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control no-html-tags'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control no-html-tags', 'rows' => 20],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }
}
