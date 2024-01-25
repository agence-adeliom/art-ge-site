<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\DashboardFilterDTO;
use App\Enum\TerritoireAreaEnum;
use App\Event\DashboardDataListsEvent;
use App\Repository\ReponseRepository;
use App\Repository\TerritoireRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DashboardDataListsEvent::class)]
class DashboardDataListsEventListener
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly ReponseRepository $reponseRepository,
    ) {
    }

    public function __invoke(DashboardDataListsEvent $event): void
    {
        $dashboardFilterDTO = $event->getDashboardFilterDTO();
        $territoires = $dashboardFilterDTO->getTerritoires();


        if([] === $territoires) {
            // no filter applied; use the default territoire data
            $territoire = $dashboardFilterDTO->getTerritoire();
            $subChildren = [];
            $children = $territoire->getTerritoiresChildren()->toArray();
            if (TerritoireAreaEnum::REGION === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]])));
                    foreach ($child->getTerritoiresChildren()->toArray() as $subChild) {
                        $subChild->setScore($this->territoireRepository->getPercentageByTerritoire($subChild));
                        $subChild->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $subChild, 'territoires' => [$subChild]])));
                        $subChildren[] = $subChild;
                    }
                }
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
