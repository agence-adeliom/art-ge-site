<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\DashboardDataScoresEvent;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use App\Repository\TypologieRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DashboardDataScoresEvent::class)]
class DashboardDataScoresEventListener
{
    public function __construct(
        private readonly ScoreRepository $scoreRepository,
        private readonly ReponseRepository $reponseRepository,
        private readonly TypologieRepository $typologieRepository,
    ) {
    }

    public function __invoke(DashboardDataScoresEvent $event): void
    {
        $dashboardFilterDTO = $event->getDashboardFilterDTO();

        $thematiques = $this->scoreRepository->getPercentagesByThematiques($dashboardFilterDTO);
        $typologies = [];
        foreach ($this->typologieRepository->getSlugs() as $typology) {
            $typologies[$typology] = $this->reponseRepository->getPercentagesByTypology($typology, $dashboardFilterDTO);
        }

        $event->setScores([
            'thematiques' => $thematiques,
            'typologies' => $typologies,
        ]);
    }
}
