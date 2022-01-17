<?php

declare(strict_types=1);

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MediaFilterType extends AbstractType
{
    public function __construct(
        private KernelInterface $kernel,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $base = $this->kernel->getProjectDir() . '/public/uploads/';
        $yearsScan = scandir($base);
        foreach($yearsScan as $year) {
            if ($year !== '.' && $year !== '..' && $year !== '.gitignore') {
                $years[$year] = $year;
            }
        }

        $builder->add('year', ChoiceType::class, [
            'placeholder' => 'select year',
            'choices'  => $years,
            'attr' => ['class' => 'form-control'],
        ])
            ->add('month', ChoiceType::class, [
                'placeholder' => 'select month',
                'choices'  => [
                    'January' => '01',
                    'February' => '02',
                    'March' => '03',
                    'April' => '04',
                    'May' => '05',
                    'June' => '06',
                    'July' => '07',
                    'August' => '08',
                    'September' => '09',
                    'October' => '10',
                    'November' => '11',
                    'December' => '12',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('show', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
