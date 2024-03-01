<?php

declare(strict_types=1);

namespace App\Form\Form;

use App\Entity\Department;
use App\Entity\Repondant;
use App\Entity\Typologie;
use App\Repository\DepartmentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepondantType extends AbstractType
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
    ) {
    }

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
            ->add('contact_by_bird', HoneypotType::class)
            ->add('restauration', CheckboxType::class, [
                'false_values' => [0, '0', 'off', 'false', false, 'no'],
            ])
            ->add('greenSpace', CheckboxType::class, [
                'false_values' => [0, '0', 'off', 'false', false, 'no'],
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
            ])
            ->add('typologie', EntityType::class, [
                'class' => Typologie::class,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function ($event): void {
                /** @var Repondant $repondant */
                $repondant = $event->getData();
                $repondant->setCountry('France');
                $code = substr((string) $repondant->getZip(), 0, 2);
                if ('67' === $code || '68' === $code) {
                    $department = $this->departmentRepository->findOneBy(['slug' => 'alsace']);
                } else {
                    $department = $this->departmentRepository->getByCode($code);
                }
                if ($department) {
                    $repondant->setDepartment($department);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repondant::class,
        ]);
    }
}
