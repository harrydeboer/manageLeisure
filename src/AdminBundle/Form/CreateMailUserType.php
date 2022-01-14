<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateMailUserType extends AbstractMailUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ]);
        parent::buildForm($builder, $options);
    }
}
