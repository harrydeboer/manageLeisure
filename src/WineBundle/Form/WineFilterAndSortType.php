<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\Country;
use App\WineBundle\Entity\Region;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This form is used for the filtering and sorting on the wine homepage.
 * It extends the filter form because creating and updating has grapes, taste profiles and regions also.
 */
class WineFilterAndSortType extends WineFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->add('year', IntegerType::class, [
            'attr' => ['min' => 1000, 'max' => 9999, 'placeholder' => 'year', 'class' => 'form-control'],
            'required' => false,
        ]);
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
        ])->add('country', ChoiceType::class, [
            'choices'  => array_merge(['' => null], $this->getCurrentUser()->getCountries()->toArray()),
            'choice_value' => 'id',
            'choice_label' => function(?Country $country) {
                return $country ? $country->getName() : 'select country';
            },
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);
        if ($options['country'] !== '' && !is_null($options['country'])) {
            $regions = $this->countryRepository->find((int) $options['country'])->getRegions()->toArray();
        } elseif (is_null($options['country'])) {
            $regions = [];
        } else {
            $regions = $this->getCurrentUser()->getRegions()->toArray();
        }
        $builder->add('region', ChoiceType::class, [
            'choices'  => array_merge(['' => null], $regions),
            'choice_value' => 'id',
            'choice_label' => function(?Region $region) {
                return $region ? $region->getName() : 'select region';
            },
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);
        $builder->add('show', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ]);
    }
}
