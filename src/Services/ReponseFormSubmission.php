<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Message\ReponseConfirmationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

readonly class ReponseFormSubmission
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReponseScoreGeneration $reponseScoreGeneration,
        private MessageBusInterface $messageBus,
    ) {}

    public function updateAndSaveReponse(Reponse $reponse): Reponse
    {
        $reponse->setCompleted(true);
        $reponse->setCreatedAt(new \DateTimeImmutable());
        $reponse->setSubmittedAt(new \DateTimeImmutable());
        $reponse->setUuid(new Ulid());
        $scoreGeneration = $this->reponseScoreGeneration->generateScore($reponse);
        $reponse->setPoints($scoreGeneration->getPoints());
        $reponse->setTotal($scoreGeneration->getTotal());
        foreach ($scoreGeneration->getScores() as $score) {
            $this->entityManager->persist($score);
        }
        $this->entityManager->persist($reponse);
        $this->entityManager->flush();
        $this->messageBus->dispatch(new ReponseConfirmationMessage($reponse));

        return $reponse;
    }
}
