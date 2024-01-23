<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\TerritoireDashboardScoresEvent;
use App\Repository\ScoreRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TerritoireDashboardScoresEvent::class)]
class TerritoireDashboardScoresEventListener
{
    public function __construct(
        private readonly ScoreRepository $scoreRepository,
    ) {
    }

    public function __invoke(TerritoireDashboardScoresEvent $event): void
    {
        $territoireFilterDTO = $event->getTerritoireFilterDTO();

        $percentagesByThematiques = $this->scoreRepository->getPercentagesByThematiques($territoireFilterDTO);
        $percentagesByThematiquesAndTypologies = $this->getPercentagesByThematiquesAndTypologies();
        $percentagesByTypologiesAndThematiques = $this->scoreRepository->getPercentagesByTypologiesAndThematiques($territoireFilterDTO); // internal use only
        $percentagesByTypology = $this->getPercentagesByTypology($percentagesByTypologiesAndThematiques);

        $event->setScores([
            'percentagesByThematiques' => $percentagesByThematiques,
            'percentagesByTypology' => $percentagesByTypology,
            'percentagesByThematiquesAndTypologies' => $percentagesByThematiquesAndTypologies,
        ]);
    }

    /**
     * @return array<mixed>
     */
    private function getPercentagesByThematiquesAndTypologies(): array
    {
        $thematiquesAndTypologies = [];
        foreach ($this->thematiques ?? [] as $key => $thematique) {
            foreach ($this->typologies ?? [] as $typology) {
                $thematiquesAndTypologies[$key][$typology] = [];
            }
        }

        return $thematiquesAndTypologies;
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
            $typologyPercentages = array_column($scores, 'value'); // la liste des pourcentages par thematique pour la typologie
            $percentagesByTypology[$typology] = round(array_sum($typologyPercentages) / count($typologyPercentages));
        }

        return $percentagesByTypology;
    }
}
