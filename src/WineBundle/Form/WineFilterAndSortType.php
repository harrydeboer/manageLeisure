<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This form is used for the filtering and sorting on the wine homepage.
 * It extends the filter form because creating and updating has grapes, taste profiles and regions also.
 */
class WineFilterAndSortType extends AbstractWineType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->add('sort', ChoiceType::class, [
            'choices' => [
                'latest' => 'createdAt_DESC',
                'oldest' => 'createdAt_ASC' ,
                'price descending' => 'price_DESC',
                'price ascending' => 'price_ASC' ,
                'rating descending' => 'rating_DESC',
                'rating ascending' => 'rating_ASC' ,
            ],
            'attr' => ['class' => 'form-control'],
        ])->add('show', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ]);
        $builder->get('country')->setRequired(false);
    }
}
