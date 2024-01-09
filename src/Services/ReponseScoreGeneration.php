<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Entity\Score;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\ThematiqueRepository;
use App\Services\ChoiceIgnorer\GreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationChoiceIgnorer;
use App\ValueObject\RepondantTypologie;
use App\ValueObject\ScoreGeneration;

class ReponseScoreGeneration
{
    public function __construct(
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly GreenSpaceChoiceIgnorer $greenSpaceChoiceIgnorer,
        private readonly RestaurationChoiceIgnorer $restaurationChoiceIgnorer,
    ) {
    }

    public function generateScore(Reponse $reponse): ScoreGeneration
    {
        /** @var array{answers: array<int, array<int>>, pointsByQuestions: array<int, int>, points: int} $processedForm */
        $processedForm = $reponse->getProcessedForm();
        $repondantTypologieVO = RepondantTypologie::fromRepondant($reponse->getRepondant());
        $points = [];
        $totals = [];

        $scores = [];
        foreach ($processedForm['pointsByQuestions'] as $questionId => $thematiquePoints) {
            /** @var \App\Entity\Question $question */
            $question = $this->thematiqueRepository->findOneBy(['id' => $questionId])?->getQuestion();
            if (false === $repondantTypologieVO->getGreenSpace()) {
                $questionChoices = $this->greenSpaceChoiceIgnorer->onlyNotIgnored($question);
            }
            if (false === $repondantTypologieVO->getRestauration()) {
                $questionChoices = $this->restaurationChoiceIgnorer->onlyNotIgnored($question);
            }
            $thematique = $this->thematiqueRepository->getOneByQuestionId($questionId);
            if ($thematique) {
                $thematiqueTotal = $this->choiceTypologieRepository->getPonderationByQuestionAndTypologie($questionId, $repondantTypologieVO, $questionChoices ?? []);
                if ($thematiqueTotal) {
                    $score = new Score();
                    $score->setPoints($thematiquePoints);
                    $score->setTotal($thematiqueTotal);
                    $score->setReponse($reponse);
                    $score->setThematique($thematique);
                    $scores[] = $score;

                    $points[] = $thematiquePoints;
                    $totals[] = $thematiqueTotal;
                }
            }
        }

        $point = array_reduce($points, fn (int $carry, int $item): int => $carry + $item, 0);
        $total = array_reduce($totals, fn (int $carry, int $item): int => $carry + $item, 0);

        return ScoreGeneration::from($point, $total, $scores);
    }
}
