<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use App\Entity\User;
use App\WineBundle\Entity\Category;
use App\WineBundle\Entity\Grape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class WineForm extends AbstractType
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

        $builder
            ->add('name', TextType::class)
            ->add('grapes', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'choices' => $user?->getGrapes(),
                'choice_value' => 'id',
                'choice_label' => function(?Grape $grape) {
                    return $grape ? $grape->getName() : '';
                },
                'group_by' => function(Grape $grape) {
                    if ($grape->getType() === 'white') {
                        return 'white';
                    }

                    return 'red';
                },
            ])
            ->add('category', ChoiceType::class, [
                'choices'  => $categories,
                'choice_value' => 'id',
                'choice_label' => function(?Category $category) {
                    return $category ? $category->getName() : 'select category';
                },
                'required' => false,
            ])
            ->add('year', IntegerType::class, [
                'attr' => ['min' => 1000, 'max' => 9999],
            ])
            ->add('rating', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.1', 'min' => '1.0', 'max' => '10.0'],
            ])
            ->add('price', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.01'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
        ;
    }

    /**
     * @return ?User
     */
    private function getCurrentUser(): ?UserInterface
    {
        return $this->security->getUser();
    }
}
