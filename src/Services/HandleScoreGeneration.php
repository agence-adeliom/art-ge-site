<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Entity\Score;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\ThematiqueRepository;
use App\ValueObject\ScoreGeneration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

readonly class HandleScoreGeneration
{
    public function __construct(
        private ThematiqueRepository $thematiqueRepository,
        private ChoiceTypologieRepository $choiceTypologieRepository,
    ) {
    }

    public function __invoke(Reponse $reponse): ScoreGeneration
    {
        /** @var array{answers: array<int, array<int>>, pointsByQuestions: array<int, int>, points: int} $processedForm */
        $processedForm = $reponse->getProcessedForm();
        $points = $processedForm['points'];
        $total = $this->choiceTypologieRepository->getTotalBasedOnTypologie(
            (int) $reponse->getRepondant()->getTypologie()->getId(),
            $reponse->getRepondant()->isRestauration(),
        );

        $scores = [];
        foreach ($processedForm['pointsByQuestions'] as $questionId => $thematiquePoints) {
            $thematique = $this->thematiqueRepository->getOneByQuestionId($questionId);
            if ($thematique) {
                $thematiqueTotal = $this->choiceTypologieRepository->getPonderationByQuestionAndTypologie($questionId, (int) $reponse->getRepondant()->getTypologie()->getId(), $reponse->getRepondant()->isRestauration());
                if ($thematiqueTotal) {
                    $score = new Score();
                    $score->setTotal($thematiqueTotal);
                    $score->setPoints($thematiquePoints);
                    $score->setReponse($reponse);
                    $score->setThematique($thematique);
                    $scores[] = $score;
                }
            }
        }

        return ScoreGeneration::from($points, $total, $scores);
    }
}
