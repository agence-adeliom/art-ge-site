<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\DashboardFilterDTO;
use App\Entity\Territoire;
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
            usort($children, static fn (Territoire $a, Territoire $b) => $a->getName() <=> $b->getName());
            if (TerritoireAreaEnum::REGION === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child, $responsesIds));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]]), $responsesIds));
                    foreach ($child->getTerritoiresChildren()->filter(static fn (Territoire $territoire): bool => $territoire->getSlug() !== '')->toArray() as $subChild) {
                        $subChild->setScore($this->territoireRepository->getPercentageByTerritoire($subChild, $responsesIds));
                        $subChild->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $subChild, 'territoires' => [$subChild]]), $responsesIds));
                        $subChildren[] = $subChild;
                    }
                }
                usort($subChildren, static fn (Territoire $a, Territoire $b) => $a->getName() <=> $b->getName());
                $lists = [
                    'departments' => $children,
                    'ots' => $subChildren,
                ];
            } elseif (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                foreach ($children as $child) {
                    $child->setScore($this->territoireRepository->getPercentageByTerritoire($child, $responsesIds));
                    $child->setNumberOfReponses($this->reponseRepository->getNumberOfReponsesGlobal(DashboardFilterDTO::from(['territoire' => $child, 'territoires' => [$child]]), $responsesIds));
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

        // remove duplicata du OT vide sans nom / (prestataire-territoire-sans-office-de-tourisme)
        $noOtCount = 0;
        if (!empty($lists['ots'])) {
            foreach ($lists['ots'] as $key => $territoire) {
                $isNoOt = $territoire->getSlug() === '' || 'prestataire-territoire-sans-office-de-tourisme' === $territoire->getSlug();
                if (!$isNoOt) {
                    continue;
                } else {
                    $noOtCount++;
                    if ($noOtCount > 1) {
                        unset($lists['ots'][$key]);
                    }
                }
            }
            $lists['ots'] = array_values($lists['ots']);
            usort($lists['ots'], static fn (Territoire $a, Territoire $b) => $a->getName() <=> $b->getName());
        }

        if (isset($lists)) {
            $event->setLists($lists);
        }
    }
}
