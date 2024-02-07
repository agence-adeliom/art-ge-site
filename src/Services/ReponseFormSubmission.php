<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Message\ReponseConfirmationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

class ReponseFormSubmission
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReponseScoreGeneration $reponseScoreGeneration,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

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

        $this->generateReponseChoicesManyToMany($reponse);

        $this->messageBus->dispatch(new ReponseConfirmationMessage((int) $reponse->getId()));

        return $reponse;
    }

    private function generateReponseChoicesManyToMany(Reponse $reponse): void
    {
        $reponseId = $reponse->getId();
        if ($reponseId) {
            $sql = 'INSERT INTO reponse_choice (reponse_id, choice_id) VALUES ';
            foreach ($reponse->getRawForm() as $choices) {
                foreach (array_keys($choices['answers']) as $choiceId) {
                    $sql .= '(?, ?),';
                    $params[] = $reponseId;
                    $params[] = $choiceId;
                }
            }
            $sql = substr($sql, 0, -1);
            if (str_contains($sql, '?')) {
                $this->entityManager->getConnection()->executeQuery($sql, $params);
            }
        }
    }
}
