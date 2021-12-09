<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\Country;
use App\WineBundle\Entity\Region;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
class WineType extends WineFilterType
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
            ->add('country', ChoiceType::class, [
                'choices'  => array_merge(['' => null], $this->getCurrentUser()->getCountries()->toArray()),
                'choice_value' => 'id',
                'choice_label' => function(?Country $country) {
                    return $country ? $country->getName() : 'select country';
                },
                'attr' => ['class' => 'form-control'],
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
        ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5],
            ])
            ->add('rating', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.1', 'min' => '1.0', 'max' => '10.0', 'class' => 'form-control'],
            ])
            ->add('review', TextareaType::class, [
                'required' => false,
                'attr' => ['step' => '0.1', 'min' => '1.0', 'max' => '10.0', 'class' => 'form-control', 'rows' => 5],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
