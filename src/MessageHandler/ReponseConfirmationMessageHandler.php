<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ReponseConfirmationMessage;
use App\Repository\ReponseRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Twig\Environment;

#[AsMessageHandler]
class ReponseConfirmationMessageHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly ParameterBagInterface $parameterBag,
        private readonly LoggerInterface $logger,
        private readonly ReponseRepository $reponseRepository,
    ) {}

    public function __invoke(ReponseConfirmationMessage $message): void
    {
        $reponseId = $message->getReponseId();
        if (! ($reponse = $this->reponseRepository->find($reponseId))) {
            return;
        }

        $recipient = $reponse->getRepondant()->getEmail();
        $emailSubject = 'Voici vos résultats';
        $emailTemplate = 'emails/confirmation.html.twig';

        try {
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
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
