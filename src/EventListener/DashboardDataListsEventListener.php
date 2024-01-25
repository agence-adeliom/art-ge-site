<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
use App\Event\DashboardDataListsEvent;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DashboardDataListsEvent::class)]
class DashboardDataListsEventListener
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly ScoreRepository $scoreRepository,
        private readonly ReponseRepository $reponseRepository,
        private readonly TypologieRepository $typologieRepository,
    ) {
    }

    public function __invoke(DashboardDataListsEvent $event): void
    {
        $dashboardFilterDTO = $event->getDashboardFilterDTO();
        $territoires = $dashboardFilterDTO->getTerritoires();

        $lists = [];

        if([] === $territoires) {
            // no filter applied; use the default territoire data
            $territoire = $dashboardFilterDTO->getTerritoire();
            $subChildren = [];
            $children = $territoire->getTerritoiresChildren()->toArray();
            if (TerritoireAreaEnum::REGION === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentagesByDepartment($child));
                    foreach ($child->getTerritoiresChildren()->toArray() as $subChild) {
                        $subChild->setScore($this->territoireRepository->getPercentagesByOT($subChild));
                        $subChildren[] = $subChild;
                    }
                }
                $this->territoireRepository->getPercentagesByTerritoires($children);
                $lists = [
                    'departments' => $children,
                    'ots' => $subChildren,
                ];
            }
        } else {

        }

        $event->setLists($lists);
    }
}
