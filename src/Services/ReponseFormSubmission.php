<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Message\GenerateReponsePDF;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

class ReponseFormSubmission
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReponseScoreGeneration $reponseScoreGeneration,
        private readonly MessageBusInterface $messageBus,
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
            $reponse->addScore($score);
        }
        $this->entityManager->persist($reponse);
        $this->entityManager->flush();
        $this->messageBus->dispatch(new GenerateReponsePDF($reponse));

        return $reponse;
    }
}
