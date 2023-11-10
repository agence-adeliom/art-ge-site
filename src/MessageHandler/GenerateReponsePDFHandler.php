<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\GenerateReponsePDF;
use App\Message\ReponseConfirmationMessage;
use App\Repository\ReponseRepository;
use App\Services\ReponsePDFGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class GenerateReponsePDFHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus,
        private readonly ReponseRepository $reponseRepository,
        private readonly ReponsePDFGenerator $reponsePDFGenerator,
    ) {}

    public function __invoke(GenerateReponsePDF $message): void
    {
        $reponseId = $message->getReponseId();
        if (!($reponse = $this->reponseRepository->find($reponseId))) {
            return;
        }

        try {
            $this->reponsePDFGenerator->generatePdf($reponse);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            throw new UnrecoverableMessageHandlingException();
        } finally {
            $this->messageBus->dispatch(new ReponseConfirmationMessage($reponse));
        }
    }
}
