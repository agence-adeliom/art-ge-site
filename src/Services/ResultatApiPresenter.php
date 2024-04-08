<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Choice;
use App\Entity\Reponse;
use App\Entity\Score;
use App\Services\ChoiceIgnorer\GreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationAndGreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationChoiceIgnorer;

class ResultatApiPresenter
{
    public function __construct(
        private readonly PercentagePresenter $percentagePresenter,
        private readonly RestaurationChoiceIgnorer $restaurationChoiceIgnorer,
        private readonly GreenSpaceChoiceIgnorer $greenSpaceChoiceIgnorer,
        private readonly RestaurationAndGreenSpaceChoiceIgnorer $restaurationAndGreenSpaceChoiceIgnorer,
    ) {
    }

    /** @return array<mixed> */
    public function present(Reponse $reponse): array
    {
        $choiceMapper = fn (Choice $choice): array => [
            'name' => htmlentities($choice->getLibelle()),
            'slug' => $choice->getSlug(),
        ];

        $removeZeroChoice = fn (array $choice): bool => 'je-n-ai-rien-entrepris-en-ce-sens' !== $choice['slug'];

        $isRestauration = $reponse->getRepondant()->isRestauration();
        $isGreenSpace = $reponse->getRepondant()->isGreenSpace();

        $removeNonApplicableChoices = function (Choice $choice) use ($isRestauration, $isGreenSpace): bool {
            if (in_array($choice->getSlug(), ['je-n-ai-rien-entrepris-en-ce-sens', 'je-ne-suis-pas-concerne-e-car-je-n-ai-pas-d-equipe'])) {
                return false;
            }

            $choicesToKeep = [];
            if (!$isRestauration && !$isGreenSpace && $choice->getQuestion()) {
                $choicesToKeep = $this->restaurationAndGreenSpaceChoiceIgnorer->onlyNotIgnored($choice->getQuestion());
            } elseif (!$isGreenSpace && $isRestauration && $choice->getQuestion()) {
                $choicesToKeep = $this->greenSpaceChoiceIgnorer->onlyNotIgnored($choice->getQuestion());
            } elseif ($isGreenSpace && !$isRestauration && $choice->getQuestion()) {
                $choicesToKeep = $this->restaurationChoiceIgnorer->onlyNotIgnored($choice->getQuestion());
            }

            if ([] !== $choicesToKeep && null !== $choicesToKeep) {
                return in_array($choice->getId(), $choicesToKeep);
            }

            return true;
        };

        $scores = array_map(fn (Score $score): array => [
            'name' => htmlentities($score->getThematique()->getName()),
            'slug' => $score->getThematique()->getSlug(),
            'links' => array_values($score->getThematique()->getLinks() ?? []),
            'points' => $score->getPoints(),
            'total' => $score->getTotal(),
            'percentage' => $this->percentagePresenter->displayPercentage((int) $score->getPoints(), $score->getTotal()),
            'chosenChoices' => array_filter(array_map($choiceMapper, $score->getChosenChoices()), $removeZeroChoice),
            'notChosenChoices' => array_values(array_map($choiceMapper, array_filter($score->getNotChosenChoices(), $removeNonApplicableChoices))),
        ], $reponse->getScores()->toArray());

        foreach ($scores as $kS => $score) {
            if (null !== $score['links'] && [] !== $score['links']) {
                foreach ($score['links'] as $key => $link) {
                    $scores[$kS]['links'][$key]['label'] = htmlentities($link['label']);
                }
            }
        }

        return [
            'reponsePercentage' => $this->percentagePresenter->displayPercentage($reponse),
            'submitDate' => $reponse->getSubmittedAt()?->format('d.m.Y'),
            'scores' => $scores,
        ];
    }
}
