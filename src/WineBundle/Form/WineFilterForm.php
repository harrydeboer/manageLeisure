<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\User;
use App\WineBundle\Entity\Category;
use App\WineBundle\Entity\Grape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class WineFilterForm extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->getCurrentUser();
        if (is_null($user)) {
            $categories = ['' => null];
        } else {
            $categories = array_merge(['' => null], $user?->getCategories()->toArray());
        }

        $builder->add('redGrapes', ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $user?->getRedGrapes(),
            'choice_value' => 'id',
            'choice_label' => function(?Grape $grape) {
                return $grape ? $grape->getName() : '';
            },
            'attr' => ['class' => 'form-control'],
        ])
            ->add('whiteGrapes', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'choices' => $user?->getWhiteGrapes(),
                'choice_value' => 'id',
                'choice_label' => function(?Grape $grape) {
                    return $grape ? $grape->getName() : '';
                },
                'attr' => ['class' => 'form-control'],
            ])
            ->add('category', ChoiceType::class, [
                'choices'  => $categories,
                'choice_value' => 'id',
                'choice_label' => function(?Category $category) {
                    return $category ? $category->getName() : 'select category';
                },
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }


    /**
     * @return ?User
     */
    private function getCurrentUser(): ?UserInterface
    {
        return $this->security->getUser();
    }
}
