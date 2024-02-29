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
        $responsesIds = $event->getReponsesIds();

        if ([] === $territoires) {
            // no filter applied; use the default territoire data
            $territoire = $dashboardFilterDTO->getTerritoire();
            $subChildren = [];
            $children = $territoire->getTerritoiresChildren()->toArray();
            if (TerritoireAreaEnum::REGION === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child, $responsesIds));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]])));
                    foreach ($child->getTerritoiresChildren()->toArray() as $subChild) {
                        $subChild->setScore($this->territoireRepository->getPercentageByTerritoire($subChild, $responsesIds));
                        $subChild->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $subChild, 'territoires' => [$subChild]])));
                        $subChildren[] = $subChild;
                    }
                }
                $lists = [
                    'departments' => $children,
                    'ots' => $subChildren,
                ];
            } elseif (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child, $responsesIds));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]])));
                }
                $lists = [
                    'ots' => $children,
                ];
            }
        } else {
            $childrens = [];
            foreach ($territoires as $territoire) {
                $children = $territoire->getTerritoiresChildren()->toArray();
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child, $responsesIds));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]])));
                    $childrens[] = $child;
                }
            }
            $lists = [
                'ots' => $childrens,
            ];
        }

        if (isset($lists)) {
            $event->setLists($lists);
        }
    }
}
