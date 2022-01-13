<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\User;
use App\Repository\CountryRepositoryInterface;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use App\WineBundle\Repository\RegionRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This form is extended by create, update and filter/sort forms.
 */
abstract class AbstractWineType extends AbstractType
{
    public function __construct(
        private TokenStorageInterface $token,
        protected CountryRepositoryInterface $countryRepository,
        protected RegionRepositoryInterface $regionRepository,
        protected GrapeRepositoryInterface $grapeRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('grapes', EntityType::class, [
            'class' => Grape::class,
            'expanded' => true,
            'multiple' => true,
            'choices' => $this->grapeRepository->findAllOrderedByName(),
            'choice_value' => 'id',
            'choice_label' => function(?Grape $grape) {
                return $grape ? $grape->getName() : '';
            },
            'attr' => ['class' => 'form-control'],
        ])
            ->add('tasteProfile', ChoiceType::class, [
                'placeholder' => 'select taste profile',
                'choices'  => $this->getUser()->getTasteProfiles()->toArray(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('year', IntegerType::class, [
                'attr' => ['min' => 1000, 'max' => 9999, 'class' => 'form-control'],
                'required' => false,
            ])
            ->add('country', ChoiceType::class, [
                'placeholder' => 'select country',
                'choices'  => $this->countryRepository->findAll(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control country-select'],
            ]);
        $regions = [];
        if ($options['country'] !== '' && !is_null($options['country'])) {
            $regions = $this->countryRepository->find((int) $options['country'])->getRegions()->toArray();
        }
        $builder->add('region', ChoiceType::class, [
            'placeholder' => 'select region',
            'choices'  => $regions,
            'choice_value' => 'id',
            'choice_label' => 'name',
            'required' => false,
            'attr' => ['class' => 'form-control region-select'],
        ]);
        $subregions = [];
        if ($options['region'] !== '' && !is_null($options['region'])) {
            $subregions = $this->regionRepository->find((int) $options['region'])->getSubregions()->toArray();
        }
        $builder->add('subregion', ChoiceType::class, [
            'placeholder' => 'select subregion',
            'choices'  => $subregions,
            'choice_value' => 'id',
            'choice_label' => 'name',
            'required' => false,
            'attr' => ['class' => 'form-control subregion-select'],
        ]);
    }

    /**
     * @return User
     */
    protected function getUser(): UserInterface
    {
        return $this->token->getToken()->getUser();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'country' => null,
            'region' => null,
        ]);
    }
}
