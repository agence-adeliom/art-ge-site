<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\TerritoireDashboardScoresEvent;
use App\Repository\ScoreRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TerritoireDashboardScoresEvent::class)]
class TerritoireDashboardScoresEventListener
{
    public function __construct(
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ScoreRepository $scoreRepository,
    ) {
    }

    public function __invoke(TerritoireDashboardScoresEvent $event): void
    {
        $territoireFilterDTO = $event->getTerritoireFilterDTO();

        $percentagesByThematiques = $this->scoreRepository->getPercentagesByThematiques($territoireFilterDTO);
        $percentagesByTypologiesAndThematiques = $this->scoreRepository->getPercentagesByTypologiesAndThematiques($territoireFilterDTO); // internal use only
        $percentagesByTypology = $this->getPercentagesByTypology($percentagesByTypologiesAndThematiques);

        $event->setScores([
            'percentagesByThematiques' => $percentagesByThematiques,
            'percentagesByTypology' => $percentagesByTypology,
        ]);
    }

    /**
     * @param array<mixed> $percentagesByTypologiseAndThematiques
     *
     * @return array<string, float>
     */
    private function getPercentagesByTypology(array $percentagesByTypologiseAndThematiques): array
    {
        $percentagesByTypology = [];

        foreach ($percentagesByTypologiseAndThematiques as $typology => $scores) {
            if (empty($scores)) {
                continue;
            }
            $typologyPercentages = array_column($scores, 'value'); // la liste des pourcentages par thematique pour la typologie
            $percentagesByTypology[$typology] = round(array_sum($typologyPercentages) / count($typologyPercentages));
        }

        return $percentagesByTypology;
    }
}
