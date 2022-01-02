<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\WineBundle\Entity\Country;
use App\Entity\User;
use App\WineBundle\Repository\CountryRepositoryInterface;
use App\WineBundle\Entity\Region;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Entity\Grape;
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
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('grapes', EntityType::class, [
            'class' => Grape::class,
            'expanded' => true,
            'multiple' => true,
            'choices' => $this->getUser()->getGrapes(),
            'choice_value' => 'id',
            'choice_label' => function(?Grape $grape) {
                return $grape ? $grape->getName() : '';
            },
            'attr' => ['class' => 'form-control'],
        ])
            ->add('tasteProfile', ChoiceType::class, [
                'choices'  => array_merge(['' => null], $this->getUser()->getTasteProfiles()->toArray()),
                'choice_value' => 'id',
                'choice_label' => function(?TasteProfile $tasteProfile) {
                    return $tasteProfile ? $tasteProfile->getName() : 'select taste profile';
                },
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('year', IntegerType::class, [
                'attr' => ['min' => 1000, 'max' => 9999, 'class' => 'form-control'],
                'required' => false,
            ])
            ->add('country', ChoiceType::class, [
                'choices'  => array_merge(['' => null], $this->getUser()->getCountries()->toArray()),
                'choice_value' => 'id',
                'choice_label' => function(?Country $country) {
                    return $country ? $country->getName() : 'select country';
                },
                'attr' => ['class' => 'form-control country-select'],
            ]);
        $regions = [];
        if ($options['country'] !== '' && !is_null($options['country'])) {
            $regions = $this->countryRepository->find((int) $options['country'])->getRegions()->toArray();
        }
        $builder->add('region', ChoiceType::class, [
            'choices'  => array_merge(['' => null], $regions),
            'choice_value' => 'id',
            'choice_label' => function(?Region $region) {
                return $region ? $region->getName() : 'select region';
            },
            'attr' => ['class' => 'form-control region-select'],
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
        ]);
    }
}
