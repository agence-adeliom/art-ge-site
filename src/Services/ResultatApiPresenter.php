<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Choice;
use App\Entity\Reponse;
use App\Entity\Score;

class ResultatApiPresenter
{
    public function __construct(
        private readonly PercentagePresenter $percentagePresenter,
    ) {
    }

    /** @return array<mixed> */
    public function present(Reponse $reponse): array
    {
        $choiceMapper = fn (Choice $choice): array => [
            'name' => htmlentities($choice->getLibelle()),
            'slug' => $choice->getSlug(),
        ];

        $removeZeroChoice = fn (array $choice): bool => $choice['slug'] !== 'je-n-ai-rien-entrepris-en-ce-sens';

        $scores = array_map(fn (Score $score): array => [
            'name' => htmlentities($score->getThematique()->getName()),
            'slug' => $score->getThematique()->getSlug(),
            'links' => array_values($score->getThematique()->getLinks() ?? []),
            'points' => $score->getPoints(),
            'total' => $score->getTotal(),
            'percentage' => $this->percentagePresenter->displayPercentage((int) $score->getPoints(), $score->getTotal()),
            'chosenChoices' => array_filter(array_map($choiceMapper, $score->getChosenChoices()), $removeZeroChoice),
            'notChosenChoices' => array_filter(array_map($choiceMapper, $score->getNotChosenChoices()), $removeZeroChoice),
        ], $reponse->getScores()->toArray());

        return [
            'reponsePercentage' => $this->percentagePresenter->displayPercentage($reponse),
            'submitDate' => $reponse->getSubmittedAt()?->format('d.m.Y'),
            'scores' => $scores,
        ];
    }
}
