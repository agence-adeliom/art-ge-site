<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

readonly class HandleFormSubmission
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HandleScoreGeneration $handleScoreGeneration,
    ) {
    }

    public function __invoke(Reponse $reponse): Reponse
    {
        $reponse->setCompleted(true);
        $reponse->setCreatedAt(new \DateTimeImmutable());
        $reponse->setSubmittedAt(new \DateTimeImmutable());
        $reponse->setUuid(new Ulid());
        $scoreGeneration = ($this->handleScoreGeneration)($reponse);
        $reponse->setPoints($scoreGeneration->getPoints());
        $reponse->setTotal($scoreGeneration->getTotal());
        foreach ($scoreGeneration->getScores() as $score) {
            $this->entityManager->persist($score);
        }
        $this->entityManager->persist($reponse);
        $this->entityManager->flush();

        return $reponse;
    }
}
