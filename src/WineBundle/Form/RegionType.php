<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegionType extends AbstractType
{
    public function __construct(
        private TokenStorageInterface $token,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control no-html-tags'],
            ])
            ->add('country', ChoiceType::class, [
                'placeholder' => 'select country',
                'choices'  => $this->getUser()->getCountries()->toArray(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    /**
     * @return User
     */
    private function getUser(): UserInterface
    {
        return $this->token->getToken()->getUser();
    }
}
