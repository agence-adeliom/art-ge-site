<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\DashboardFilterDTO;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\ORM\EntityManagerInterface;

class ResponseIdsSelector
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getLastReponsesIds(DashboardFilterDTO $dashboardFilterDTO): array
    {
        $zipCriteria = '';
        $zipCriterias = [];
        $zipParams = [];
        $territoires = $dashboardFilterDTO->getTerritoires();
        if ([] !== $territoires) {
            foreach ($territoires as $key => $territoire) {
                if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
                    if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                        $department = DepartementEnum::tryFrom($territoire->getSlug());
                        if ($department) {
                            if (DepartementEnum::ALSACE === $department) {
                                $zipCriterias[] = ' U.zip BETWEEN :zip67' . $key . ' AND :zip69' . $key . ' ';
                                $zipParams['zip67' . $key] = '67%';
                                $zipParams['zip69' . $key] = '69%';
                            } else {
                                $zipCriterias[] = ' U.zip LIKE :zip' . $key . ' ';
                                $zipParams['zip' . $key] = DepartementEnum::getCode($department) . '%';
                            }
                        }
                    } else {
                        $zipCriterias[] = ' U.zip IN (:zip' . $key . ') ';
                        $zipParams['zip' . $key] = $territoire->getZips();
                    }
                }
            }
            if ([] !== $zipCriterias) {
                $zipCriteria = 'AND (' . implode(' OR ', $zipCriterias) . ') ';
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
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->format($dateFormat);
                $dateParams['to'] = $dashboardFilterDTO->getTo()->format($dateFormat);
            } elseif (null !== $dashboardFilterDTO->getFrom() && null === $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at >= :from';
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->format($dateFormat);
            } elseif (null === $dashboardFilterDTO->getFrom() && null !== $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at <= :to';
                $dateParams['to'] = $dashboardFilterDTO->getTo()->format($dateFormat);
            }
        }

        return $this->entityManager->getConnection()->executeQuery('
            SELECT R.id, MAX(R.submitted_at)
            FROM reponse R
            INNER JOIN repondant U ON U.id = R.repondant_id 
            INNER JOIN typologie TY ON TY.id = U.typologie_id 
            WHERE 1 = 1
                    ' . $typologyCriteria . '
                    ' . $zipCriteria . '
                    ' . $dateCriteria . '
            GROUP BY R.repondant_id', [...$typologyParams, ...$zipParams, ...$dateParams])->fetchFirstColumn();
    }
}
