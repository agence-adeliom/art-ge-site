<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Entity\Score;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\ThematiqueRepository;
use App\ValueObject\RepondantTypologie;
use App\ValueObject\ScoreGeneration;

readonly class ReponseScoreGeneration
{
    public function __construct(
        private ThematiqueRepository $thematiqueRepository,
        private ChoiceTypologieRepository $choiceTypologieRepository,
    ) {}

    public function generateScore(Reponse $reponse): ScoreGeneration
    {
        /** @var array{answers: array<int, array<int>>, pointsByQuestions: array<int, int>, points: int} $processedForm */
        $processedForm = $reponse->getProcessedForm();
        $repondantTypologieVO = RepondantTypologie::fromRepondant($reponse->getRepondant());
        $points = $processedForm['points'];
        $total = $this->choiceTypologieRepository->getTotalBasedOnTypologie($repondantTypologieVO);

        $scores = [];
        foreach ($processedForm['pointsByQuestions'] as $questionId => $thematiquePoints) {
            $thematique = $this->thematiqueRepository->getOneByQuestionId($questionId);
            if ($thematique) {
                $thematiqueTotal = $this->choiceTypologieRepository->getPonderationByQuestionAndTypologie($questionId, $repondantTypologieVO);
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
