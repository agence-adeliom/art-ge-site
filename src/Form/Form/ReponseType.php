<?php

declare(strict_types=1);

namespace App\Form\Form;

use App\Entity\Reponse;
use App\EventListener\Form\EmailToRepondantListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function __construct(
        private readonly EmailToRepondantListener $emailToRepondantListener,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('repondant', RepondantType::class)
            ->add('rawForm', RawFormReponseType::class)
            ->add('processedForm', ProcessedFormReponseType::class)
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event): void {
                $data = $event->getData();
                if (!empty($data['rawForm'])) {
                    // on copie les données de rawForm dans processedForm pour les traiter dans son DataTransformer
                    $data['processedForm'] = $data['rawForm'];
                    $event->setData($data);
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, $this->emailToRepondantListener)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
            'csrf_protection' => false,
        ]);
    }
}
