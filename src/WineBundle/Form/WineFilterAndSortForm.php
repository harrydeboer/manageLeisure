<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class WineFilterAndSortForm extends WineFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->add('filter', ChoiceType::class, [
        'choices' => [
            'latest' => 'createdAt;DESC',
            'oldest' => 'createdAt;ASC' ,
            'price descending' => 'price;DESC',
            'price ascending' => 'price;ASC' ,
            'rating descending' => 'rating;DESC',
            'rating ascending' => 'rating;ASC' ,
            ],
        ]);
    }
}
