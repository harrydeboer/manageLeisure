<?php

declare(strict_types=1);

namespace App\WineBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class UpdateWineType extends WineType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->get('label')->setRequired(false);
    }
}
