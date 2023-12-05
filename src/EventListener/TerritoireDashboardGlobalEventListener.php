<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\TerritoireDashboardGlobalEvent;
use App\Repository\ReponseRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TerritoireDashboardGlobalEvent::class)]
class TerritoireDashboardGlobalEventListener
{
    public function __construct(
        private readonly ReponseRepository $reponseRepository,
    ) {
    }

    public function __invoke(TerritoireDashboardGlobalEvent $event): void
    {
        $territoireFilterDTO = $event->getTerritoireFilterDTO();

        $event->setGlobals([
            'repondantsGlobal' => $this->reponseRepository->getRepondantsGlobal($territoireFilterDTO),
            'repondantsByTypologieGlobal' => $this->reponseRepository->getRepondantsByTypologieGlobal($territoireFilterDTO),
            'numberOfReponsesGlobal' => $this->reponseRepository->getNumberOfReponsesGlobal($territoireFilterDTO), // 29 bas-rhin
            'numberOfReponsesRegionGlobal' => $this->reponseRepository->getNumberOfReponsesRegionGlobal(), // 300 région,
            'percentageGlobal' => $this->reponseRepository->getPercentageGlobal($territoireFilterDTO), // 58%
            'percentageRegionGlobal' => $this->reponseRepository->getPercentageRegionGlobal(), // 56%
        ]);
    }

}
