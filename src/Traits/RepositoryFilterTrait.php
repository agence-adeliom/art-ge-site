<?php

declare(strict_types=1);

namespace App\Traits;

use App\Dto\DashboardFilterDTO;
use App\Dto\FilterDateDTOInterface;
use App\Dto\FilterTypologyDTOInterface;
use App\Dto\TerritoireFilterDTO;
use App\Entity\Territoire;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\ORM\QueryBuilder;

trait RepositoryFilterTrait
{
    private function filterByAreaZipCodes(QueryBuilder $qb, Territoire $territoire, int $key = 0): QueryBuilder
    {
        if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
            $ors = [];
            if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                $department = DepartementEnum::tryFrom($territoire->getSlug());
                if ($department) {
                    $ors[] = $qb->expr()->like('u.zip', ':zip' . $key);
                    $qb->setParameter('zip' . $key, DepartementEnum::getCode($department) . '%');
                }
            } else {
                $ors[] = $qb->expr()->in('u.zip', ':zip' . $key);
                $qb->setParameter('zip' . $key, $territoire->getZips());
            }
            if ([] !== $ors) {
                $addJoin = true;
                foreach ($qb->getDQLPart('join') as $rootJoins) {
                    foreach ($rootJoins as $joins) {
                        if ('r.repondant' === $joins->getJoin()) {
                            $addJoin = false;
                        }
                    }
                }
                if ($addJoin) {
                    $qb->innerJoin('r.repondant', 'u');
                }

                $qb->orWhere($qb->expr()->andX(...$ors));
            }
        }

        return $qb;
    }

    private function filterByTypology(QueryBuilder $qb, FilterTypologyDTOInterface $filterTypologyDTO): QueryBuilder
    {
        if (!empty($filterTypologyDTO->getTypologies())) {
            $ors = [];
            foreach ($filterTypologyDTO->getTypologies() as $key => $typologie) {
                $ors[] = $qb->expr()->eq('ty.slug', ':typologie' . $key);
                $qb->setParameter('typologie' . $key, $typologie);
            }
            $qb->andWhere($qb->expr()->orX(...$ors));
        }

        return $qb;
    }

    private function filterByDateRange(QueryBuilder $qb, FilterDateDTOInterface $filterDateDTO): QueryBuilder
    {
        if ($filterDateDTO->hasDateRange()) {
            $dateFormat = 'Y-m-d H:i:s';
            if (null !== $filterDateDTO->getFrom() && null !== $filterDateDTO->getTo()) {
                $qb->andWhere('r.submittedAt BETWEEN :from AND :to')
                    ->setParameter('from', $filterDateDTO->getFrom()->format($dateFormat))
                    ->setParameter('to', $filterDateDTO->getTo()->format($dateFormat))
                ;
            } elseif (null !== $filterDateDTO->getFrom() && null === $filterDateDTO->getTo()) {
                $qb->andWhere('r.submittedAt >= :from')
                    ->setParameter('from', $filterDateDTO->getFrom()->format($dateFormat))
                ;
            } elseif (null === $filterDateDTO->getFrom() && null !== $filterDateDTO->getTo()) {
                $qb->andWhere('r.submittedAt <= :to')
                    ->setParameter('to', $filterDateDTO->getTo()->format($dateFormat))
                ;
            }
        }

        return $qb;
    }

    private function addFilters(QueryBuilder $qb, DashboardFilterDTO | TerritoireFilterDTO $filterDTO): QueryBuilder
    {
        if ($filterDTO instanceof TerritoireFilterDTO || ($filterDTO instanceof DashboardFilterDTO && [] === $filterDTO->getTerritoires())) {
            $qb = $this->filterByAreaZipCodes($qb, $filterDTO->getTerritoire());
        }

        if ($filterDTO instanceof DashboardFilterDTO) {
            $territoires = $filterDTO->getTerritoires();
            if ([] !== $territoires) {
                foreach ($territoires as $key => $territoire) {
                    $qb = $this->filterByAreaZipCodes($qb, $territoire, $key);
                }
            }
        }

        return $this->addFiltersTypologyAndDateToQueryBuilder($qb, $filterDTO);
    }

    private function addFiltersTypologyAndDateToQueryBuilder(QueryBuilder $qb, FilterDateDTOInterface & FilterTypologyDTOInterface $territoireFilterDTO): QueryBuilder
    {
        $qb = $this->filterByTypology($qb, $territoireFilterDTO);

        return $this->filterByDateRange($qb, $territoireFilterDTO);
    }
}
