<?php

namespace App\Form\Form;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcessedFormReponseType extends CollectionType
{
    public function __construct(
        private readonly ProcessedFormReponseDataTransformer $processedFormReponseDataTransformer,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer($this->processedFormReponseDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'entry_type' => QuestionType::class,
            'allow_extra_fields' => true,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }
}
