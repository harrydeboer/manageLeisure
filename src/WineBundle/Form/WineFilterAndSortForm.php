<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class WineFilterAndSortForm extends WineFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->add('year', IntegerType::class, [
            'attr' => ['min' => 1000, 'max' => 9999, 'placeholder' => 'year', 'class' => 'form-control'],
            'required' => false,
        ]);
        $builder->add('filter', ChoiceType::class, [
            'choices' => [
                'latest' => 'createdAt_DESC',
                'oldest' => 'createdAt_ASC' ,
                'price descending' => 'price_DESC',
                'price ascending' => 'price_ASC' ,
                'rating descending' => 'rating_DESC',
                'rating ascending' => 'rating_ASC' ,
            ],
            'attr' => ['class' => 'form-control'],
        ]);
        $builder->add('show', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ]);
    }
}
