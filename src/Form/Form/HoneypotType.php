<?php

declare(strict_types=1);

namespace App\Form\Form;

use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class HoneypotType extends EmailType
{
    final public const NAME = 'honeypot';

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            $form = $event->getForm();
            $formParent = $form->getParent();

            if (!$data) {
                return;
            }

            if ($formParent && $options['causesError']) {
                $error = sprintf("%s - HoneyPot field triggered by '%s' - Form is invalid", $formParent->getName(), $data);
                $this->logger->info($error);
                $formParent->addError(new FormError('HoneyPot field triggered'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'mapped' => false,
            'data' => '',
            'causesError' => true,
            'attr' => [
                // autocomplete="off" does not work in some cases, random strings always do
                'autocomplete' => 'nope',
                // Make the field unfocusable for keyboard users
                'tabindex' => -1,
                // Hide the field from assistive technology like screen readers
                'aria-hidden' => 'true',
                // Fake `display:none` css behaviour to hide input
                // as some bots may also check inputs visibility
                'style' => 'position: fixed; left: -100%; top: -100%;',
            ],
        ]);
    }
}
