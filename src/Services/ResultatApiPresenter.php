<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Choice;
use App\Entity\Reponse;
use App\Entity\Score;

class ResultatApiPresenter
{
    public function __construct(
        private PercentagePresenter $percentagePresenter,
    ) {
    }

    /** @return array<mixed> */
    public function present(Reponse $reponse): array
    {
        $choiceMapper = function (Choice $choice) {
            return [
                'name' => htmlentities($choice->getLibelle()),
                'slug' => $choice->getSlug(),
            ];
        };

        $scores = array_map(function (Score $score) use ($choiceMapper) {
            return [
                'name' => htmlentities($score->getThematique()->getName()),
                'slug' => $score->getThematique()->getSlug(),
                'points' => $score->getPoints(),
                'total' => $score->getTotal(),
                'percentage' => $this->percentagePresenter->displayPercentage($score->getPoints(), $score->getTotal()),
                'chosenChoices' => array_map($choiceMapper, $score->getChosenChoices()),
                'notChosenChoices' => array_map($choiceMapper, $score->getNotChosenChoices()),
            ];
        }, $reponse->getScores()->toArray());

        return [
            'reponsePercentage' => $this->percentagePresenter->displayPercentage($reponse),
            'submitDate' => $reponse->getSubmittedAt()->format('d.m.Y'),
            'scores' => $scores,
        ];
    }

}
