<?php

declare(strict_types=1);

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class ThematiqueLinkAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ],
                'choices' => [
                    'Lien vers un document' => 'doc',
                    'Lien externe' => 'link',
                    'Lien vers une vidéo' => 'video',
                ],
            ])
            ->add('label', TextType::class, [
                'required' => true,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ],
            ])
            ->add('link', UrlType::class, [
                'required' => true,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ],
            ])
        ;
    }
}
