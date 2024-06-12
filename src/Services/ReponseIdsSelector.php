<?php

declare(strict_types=1);

namespace App\Services;

use App\Controller\Api\DashboardDataController;
use App\Dto\DashboardFilterDTO;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\ORM\EntityManagerInterface;

class ReponseIdsSelector
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getLastReponsesIds(DashboardFilterDTO $dashboardFilterDTO): array
    {
        $inseeCriteria = '';
        $inseeCriterias = [];
        $inseeCriteriasDpt = [];
        $inseeParams = [];
        if ([] !== $dashboardFilterDTO->getTerritoires()) {
            $territoires = DashboardDataController::excludeParentDepartmentIfOT($dashboardFilterDTO->getTerritoire(), $dashboardFilterDTO->getTerritoires());
        } else {
            $territoires = array_values(array_merge([$dashboardFilterDTO->getTerritoire()], $dashboardFilterDTO->getTerritoires()));
        }
        if ([] !== $territoires) {
            foreach ($territoires as $key => $territoire) {
                if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
                    if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                        $department = DepartementEnum::tryFrom($territoire->getSlug());
                        if ($department) {
                            if (DepartementEnum::ALSACE === $department) {
                                $inseeCriteriasDpt[] = ' (U.insee BETWEEN :insee67' . $key . ' AND :insee69' . $key . ') ';
                                $inseeParams['insee67' . $key] = '67%';
                                $inseeParams['insee69' . $key] = '69%';
                            } else {
                                $inseeCriteriasDpt[] = ' U.insee LIKE :insee' . $key . ' ';
                                $inseeParams['insee' . $key] = DepartementEnum::getCode($department) . '%';
                            }
                            if ($territoire->getCitiesToRemove()->count() > 0) {
                                $inseeCriteriasDpt[] = ' U.insee NOT IN ("' . implode('","', $territoire->getInseesToRemove()) . '") ';
                            }

                            if ([] !== $inseeCriteriasDpt) {
                                $inseeCriterias[] = '(' . implode(' AND ', $inseeCriteriasDpt) . ')';
                            }
                            if ($territoire->getCitiesToAdd()->count() > 0) {
                                $inseeCriterias[count($inseeCriterias) - 1] = $inseeCriterias[count($inseeCriterias) - 1] . ' OR U.insee IN ("' . implode('","', $territoire->getInseesToAdd()) . '") ';
                            }
                        }
                    } else {
                        $inseeCriterias[] = ' U.insee IN ("' . implode('","', $territoire->getInsees()) . '") ';
                    }
                }
            }
            if ([] !== $inseeCriterias) {
                $inseeCriteria = 'AND (' . implode(' OR ', $inseeCriterias) . ') ';
            }
        }

        $typologyCriteria = '';
        $typologyParams = [];
        if ([] !== $dashboardFilterDTO->getTypologies()) {
            $typologyCriteriaTemp = [];
            $typologyCriteria = 'AND (';
            foreach ($dashboardFilterDTO->getTypologies() as $key => $typology) {
                $typologyCriteriaTemp[] = 'TY.slug = :typology' . $key;
                $typologyParams['typology' . $key] = $typology;
            }
            $typologyCriteria .= implode(' OR ', $typologyCriteriaTemp) . ') ';
        }

        $dateCriteria = '';
        $dateParams = [];
        if ($dashboardFilterDTO->hasDateRange()) {
            $dateFormat = 'Y-m-d H:i:s';
            $dateCriteria = 'AND ';
            if (null !== $dashboardFilterDTO->getFrom() && null !== $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at BETWEEN :from AND :to';
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->setTime(0,0)->format($dateFormat);
                $dateParams['to'] = $dashboardFilterDTO->getTo()->setTime(23,59)->format($dateFormat);
            } elseif (null !== $dashboardFilterDTO->getFrom() && null === $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at >= :from';
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->setTime(0,0)->format($dateFormat);
            } elseif (null === $dashboardFilterDTO->getFrom() && null !== $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at <= :to';
                $dateParams['to'] = $dashboardFilterDTO->getTo()->setTime(23,59)->format($dateFormat);
            }
        }

        return $this->entityManager->getConnection()->executeQuery('
            SELECT MAX(R.id) as id, MAX(R.submitted_at) as submitted_at 
            FROM reponse R
            INNER JOIN repondant U ON U.id = R.repondant_id
            INNER JOIN typologie TY ON TY.id = U.typologie_id
            WHERE 1 = 1
                    ' . $typologyCriteria . '
                    ' . $inseeCriteria . '
                    ' . $dateCriteria . '
            GROUP BY R.repondant_id', [...$typologyParams, ...$inseeParams, ...$dateParams])->fetchFirstColumn();
    }
}
