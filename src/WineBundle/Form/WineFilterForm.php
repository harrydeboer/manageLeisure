<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\User;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Entity\Grape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class WineFilterForm extends AbstractType
{
    public function __construct(
        private TokenStorageInterface $token,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->getCurrentUser();

        $builder->add('grapes', EntityType::class, [
            'class' => Grape::class,
            'expanded' => true,
            'multiple' => true,
            'choices' => $user->getGrapes(),
            'choice_value' => 'id',
            'choice_label' => function(?Grape $grape) {
                return $grape ? $grape->getName() : '';
            },
            'attr' => ['class' => 'form-control'],
        ])
            ->add('tasteProfile', ChoiceType::class, [
                'choices'  => array_merge(['' => null], $user->getTasteProfiles()->toArray()),
                'choice_value' => 'id',
                'choice_label' => function(?TasteProfile $tasteProfile) {
                    return $tasteProfile ? $tasteProfile->getName() : 'select taste profile';
                },
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }


    /**
     * @return User
     */
    private function getCurrentUser(): UserInterface
    {
        return $this->token->getToken()->getUser();
    }
}
