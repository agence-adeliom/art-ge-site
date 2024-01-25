<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\DashboardDataGlobalEvent;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DashboardDataGlobalEvent::class)]
class DashboardDataGlobalEventListener
{
    public function __construct(
        private readonly ReponseRepository $reponseRepository,
        private readonly ScoreRepository $scoreRepository,
    ) {
    }

    public function __invoke(DashboardDataGlobalEvent $event): void
    {
        $dashboardFilterDTO = $event->getDashboardFilterDTO();

        $repondants = $this->reponseRepository->getRepondantsGlobal($dashboardFilterDTO);
        $event->setGlobals([
            'repondantsList' => $repondants,
            'repondantsCount' => count($repondants), // 29 bas-rhin
            'score' => $this->reponseRepository->getPercentageGlobal($dashboardFilterDTO),
            'piliersTODO' => $this->scoreRepository->getPercentagesByPiliersGlobal(), // TODO Make dynamic given the filters
        ]);
    }
}
