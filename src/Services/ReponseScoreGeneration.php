<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use App\Entity\Score;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\ThematiqueRepository;
use App\ValueObject\RepondantTypologie;
use App\ValueObject\ScoreGeneration;

class ReponseScoreGeneration
{
    public function __construct(
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly GreenSpaceChoiceExcluder $greenSpaceChoiceExcluder,
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
            /** @var \App\Entity\Question $question */
            $question = $this->thematiqueRepository->findOneBy(['id' => $questionId])?->getQuestion();
            if (false === $repondantTypologieVO->getGreenSpace()) {
                $questionChoices = $this->greenSpaceChoiceExcluder->onlyChoices($question);
            }
            $thematique = $this->thematiqueRepository->getOneByQuestionId($questionId);
            if ($thematique) {
                $thematiqueTotal = $this->choiceTypologieRepository->getPonderationByQuestionAndTypologie($questionId, $repondantTypologieVO, $questionChoices ?? []);
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
