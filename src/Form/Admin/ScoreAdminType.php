<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Score;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScoreAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('thematique')
            ->add('points')
            ->add('total')
            ->add('pourcentage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Score::class,
        ]);
    }
}
