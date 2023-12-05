<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\TerritoireDashboardGlobalEvent;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TerritoireDashboardGlobalEvent::class)]
class TerritoireDashboardGlobalEventListener
{
    public function __construct(
        private readonly ReponseRepository $reponseRepository,
        private readonly ScoreRepository $scoreRepository,
    ) {}

    public function __invoke(TerritoireDashboardGlobalEvent $event): void
    {
        $territoireFilterDTO = $event->getTerritoireFilterDTO();

        $percentagesByTypologiesAndThematiques = $this->scoreRepository->getPercentagesByTypologiesAndThematiques($territoireFilterDTO); // internal use only
        $percentagesByPiliersGlobal = $this->scoreRepository->getPercentagesByPiliersGlobal($percentagesByTypologiesAndThematiques);

        $event->setGlobals([
            'repondantsGlobal' => $this->reponseRepository->getRepondantsGlobal($territoireFilterDTO),
            'repondantsByTypologieGlobal' => $this->reponseRepository->getRepondantsByTypologieGlobal($territoireFilterDTO),
            'numberOfReponsesGlobal' => $this->reponseRepository->getNumberOfReponsesGlobal($territoireFilterDTO), // 29 bas-rhin
            'numberOfReponsesRegionGlobal' => $this->reponseRepository->getNumberOfReponsesRegionGlobal(), // 300 région,
            'percentageGlobal' => $this->reponseRepository->getPercentageGlobal($territoireFilterDTO), // 58%
            'percentageRegionGlobal' => $this->reponseRepository->getPercentageRegionGlobal(), // 56%
            'percentagesByPiliersGlobal' => $percentagesByPiliersGlobal,
        ]);
    }
}
