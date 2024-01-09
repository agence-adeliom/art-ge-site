<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class ReponseConfirmationMessageService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly ParameterBagInterface $parameterBag,
    ) {}

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail(Reponse $reponse): void
    {
        $recipient = $reponse->getRepondant()->getEmail();
        $emailSubject = 'ECO-BOUSSOLE – Accès à vos résultats';
        $emailTemplate = 'emails/confirmation.html.twig';

        $mailerSenderMail = $this->parameterBag->get('mailer_sender_mail');
        $mailerSenderName = $this->parameterBag->get('mailer_sender_name');

        if (is_string($mailerSenderMail) && is_string($mailerSenderName)) {
            $email = (new TemplatedEmail())
                ->sender(new Address($mailerSenderMail, $mailerSenderName))
                ->to(new Address($recipient))
                ->subject($emailSubject)
                ->htmlTemplate($emailTemplate)
                ->context([
                    'reponse' => $reponse,
                ])
            ;

            $bodyRenderer = new BodyRenderer($this->twig, $email->getContext());
            $bodyRenderer->render($email);

            $this->mailer->send($email);
        }
    }
}
