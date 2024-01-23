<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ReponseConfirmationMessage;
use App\Repository\ReponseRepository;
use App\Services\ReponseConfirmationMessageService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReponseConfirmationMessageHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ReponseRepository $reponseRepository,
        private readonly ReponseConfirmationMessageService $reponseConfirmationMessageService,
    ) {
    }

    public function __invoke(ReponseConfirmationMessage $message): void
    {
        $reponseId = $message->getReponseId();
        if (!($reponse = $this->reponseRepository->find($reponseId))) {
            return;
        }

        try {
            $this->reponseConfirmationMessageService->sendEmail($reponse);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
