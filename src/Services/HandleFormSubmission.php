<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Repository\ChoiceTypologieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

readonly class HandleFormSubmission
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChoiceTypologieRepository $choiceTypologieRepository,
    ) {
    }

    public function __invoke(Reponse $reponse): Reponse
    {
        $reponse->setCompleted(true);
        $reponse->setCreatedAt(new \DateTimeImmutable());
        $reponse->setSubmittedAt(new \DateTimeImmutable());
        $reponse->setUuid(new Ulid());
        $points = $reponse->getProcessedForm()['points'];
        $reponse->setPoints($points);
        $total = $this->choiceTypologieRepository->getTotalBasedOnTypologie(
            (int) $reponse->getRepondant()->getTypologie()->getId(),
            $reponse->getRepondant()->isRestauration(),
        );
        $reponse->setTotal($total);
        $this->entityManager->persist($reponse);
        $this->entityManager->flush();

        return $reponse;
    }
}
