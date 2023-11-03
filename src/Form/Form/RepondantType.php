<?php

namespace App\Form\Form;

use App\Entity\Department;
use App\Entity\Repondant;
use App\Entity\Typologie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepondantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('phone', TextType::class)
            ->add('company', TextType::class)
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('zip', TextType::class)
            ->add('country', TextType::class)
            ->add('restauration', CheckboxType::class)
            ->add('greenSpace', CheckboxType::class)
            ->add('department', EntityType::class, [
                'class' => Department::class,
            ])
            ->add('typologie', EntityType::class, [
                'class' => Typologie::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repondant::class,
        ]);
    }
}
