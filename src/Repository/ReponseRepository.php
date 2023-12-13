<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\TerritoireFilterDTO;
use App\Entity\Reponse;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponse>
 *
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    private function prepareQueryQB(?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r');

        if (null !== $restauration || null !== $greenSpace) {
            $qb->innerJoin('r.repondant', 'u');
        }

        if (null !== $restauration) {
            $qb->andWhere('u.restauration = :restauration')
                ->setParameter('restauration', $restauration)
            ;
        }

        if (null !== $greenSpace) {
            $qb->andWhere('u.greenSpace = :greenSpace')
                ->setParameter('greenSpace', $greenSpace)
            ;
        }

        return $qb;
    }

    public function getAverageMeanPointsQB(?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareQueryQB($restauration, $greenSpace)
            ->select('ROUND(AVG(r.points) / AVG(r.total) * 100, 2)')
        ;
    }

    public function getHighestPointsQB(?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareQueryQB($restauration, $greenSpace)
            ->select('MAX(r.points)')
        ;
    }

    public function getLowestPointsQB(?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareQueryQB($restauration, $greenSpace)
            ->select('MIN(r.points)')
        ;
    }

    /** GLOBAL */
    public function getAverageMeanPointsOfAllReponses(?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getAverageMeanPointsQB($restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getHighestPointsOfAllReponses(?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getHighestPointsQB($restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getLowestPointsOfAllReponses(?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getLowestPointsQB($restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /** BY DEPARTMENT */
    private function joinByDepartment(QueryBuilder $qb, string $slug): QueryBuilder
    {
        if (!in_array('r', array_keys($qb->getDQLPart('join')))) {
            $qb->innerJoin('r.repondant', 'u');
        }

        return $qb
            ->innerJoin('u.department', 'd')
            ->andWhere('d.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function getAverageMeanPointsOfDepartment(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getAverageMeanPointsQB($restauration, $greenSpace);
        $qb = $this->joinByDepartment($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getHighestPointsOfDepartment(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getHighestPointsQB($restauration, $greenSpace);
        $qb = $this->joinByDepartment($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getLowestPointsOfDepartment(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getLowestPointsQB($restauration, $greenSpace);
        $qb = $this->joinByDepartment($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /** BY TYPOLOGIE */
    private function joinByTypologie(QueryBuilder $qb, string $slug): QueryBuilder
    {
        if (!in_array('r', array_keys($qb->getDQLPart('join')))) {
            $qb->innerJoin('r.repondant', 'u');
        }

        return $qb
            ->innerJoin('u.typologie', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function getAverageMeanPointsOfTypologie(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getAverageMeanPointsQB($restauration, $greenSpace);
        $qb = $this->joinByTypologie($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getHighestPointsOfTypologie(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getHighestPointsQB($restauration, $greenSpace);
        $qb = $this->joinByTypologie($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getLowestPointsOfTypologie(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        $qb = $this->getLowestPointsQB($restauration, $greenSpace);
        $qb = $this->joinByTypologie($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getNumberOfReponsesGlobal(TerritoireFilterDTO $territoireFilterDTO): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
        ;

        $qb = $this->filterByAreaZipCodes($qb, $territoireFilterDTO);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getNumberOfReponsesRegionGlobal(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getRepondantsGlobal(TerritoireFilterDTO $territoireFilterDTO): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('t.name as typologie, r.uuid, u.company, MAX(r.points) as points, r.total')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 't')
            ->groupBy('u.id')
        ;

        $qb = $this->addFiltersToQueryBuilder($qb, $territoireFilterDTO);

        return $qb
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getRepondantsByTypologieGlobal(TerritoireFilterDTO $territoireFilterDTO): array
    {
        $repondantsByTypologieGlobal = [];

        $zipCriteria = '';
        $zipParams = [];
        $territoire = $territoireFilterDTO->getTerritoire();
        if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
            if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                $department = DepartementEnum::tryFrom($territoire->getSlug());
                if ($department) {
                    $zipCriteria = 'AND U.zip LIKE :zip';
                    $zipParams = ['zip' => DepartementEnum::getCode($department) . '%'];
                }
            } else {
                $zips = implode(',', $territoire->getZips());
                $zipCriteria = 'AND U.zip IN (:zip)';
                $zipParams = ['zip' => $zips];
            }
        }

        foreach ($territoireFilterDTO->getTypologies() ?? [] as $typology) {
            $repondantsByTypologieGlobal[$typology] = $this->getEntityManager()->getConnection()->executeQuery('
            SELECT COUNT(id) FROM (
                SELECT U.id FROM reponse R 
                    INNER JOIN repondant U ON U.id = R.repondant_id 
                    INNER JOIN typologie T ON T.id = U.typologie_id 
                WHERE 
                    T.slug = :slug 
                    ' . $zipCriteria . '
                GROUP BY U.id
            ) as temp;
            ', ['slug' => $typology, ...$zipParams])->fetchOne();
        }

        return $repondantsByTypologieGlobal;
    }

    public function getPercentageGlobal(TerritoireFilterDTO $territoireFilterDTO): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('ROUND(SUM(r.points) / SUM(r.total) * 100) as percentage')
        ;

        $qb = $this->filterByAreaZipCodes($qb, $territoireFilterDTO);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getPercentageRegionGlobal(): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('ROUND(SUM(r.points) / SUM(r.total) * 100) as percentage')
        ;

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function addFiltersToQueryBuilder(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        $qb = $this->filterByAreaZipCodes($qb, $territoireFilterDTO);

        $qb = $this->filterByThematique($qb, $territoireFilterDTO);

        $qb = $this->filterByTypology($qb, $territoireFilterDTO);

        $qb = $this->filterByRestauration($qb, $territoireFilterDTO);

        $qb = $this->filterByGreenSpace($qb, $territoireFilterDTO);

        return $this->filterByDateRange($qb, $territoireFilterDTO);
    }

    private function filterByAreaZipCodes(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        $territoire = $territoireFilterDTO->getTerritoire();
        if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
            $ors = [];
            if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                $department = DepartementEnum::tryFrom($territoire->getSlug());
                if ($department) {
                    $ors[] = $qb->expr()->like('u.zip', ':zip');
                    $qb->setParameter('zip', DepartementEnum::getCode($department) . '%');
                }
            } else {
                $zips = implode(',', $territoire->getZips());
                $ors[] = $qb->expr()->in('u.zip', ':zip');
                $qb->setParameter('zip', $zips);
            }
            if ([] !== $ors) {
                if (isset($qb->getDQLPart('join')['r'])) {
                    $addJoin = true;
                    foreach ($qb->getDQLPart('join')['r'] ?? [] as $joins) {
                        if ('r.repondant' === $joins->getJoin()) {
                            $addJoin = false;
                        }
                    }

                    if ($addJoin) {
                        $qb->innerJoin('r.repondant', 'u');
                    }
                } else {
                    $qb->innerJoin('r.repondant', 'u');
                }

                $qb->andWhere($qb->expr()->andX(...$ors));
            }
        }

        return $qb;
    }

    private function filterByThematique(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        if (!empty($territoireFilterDTO->getThematiques())) {
            $qb->innerJoin('s.thematique', 'th');
            $ors = [];
            foreach ($territoireFilterDTO->getThematiques() as $key => $thematique) {
                $ors[] = $qb->expr()->eq('th.slug', ':thematique' . $key);
                $qb->setParameter('thematique' . $key, $thematique);
            }
            $qb->andWhere($qb->expr()->orX(...$ors));
        }

        return $qb;
    }

    private function filterByTypology(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        if (!empty($territoireFilterDTO->getTypologies())) {
            $ors = [];
            foreach ($territoireFilterDTO->getTypologies() as $key => $typologie) {
                $ors[] = $qb->expr()->eq('t.slug', ':typologie' . $key);
                $qb->setParameter('typologie' . $key, $typologie);
            }
            $qb->andWhere($qb->expr()->orX(...$ors));
        }

        return $qb;
    }

    private function filterByRestauration(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        if (null !== $territoireFilterDTO->getRestauration()) {
            $qb->andWhere('u.restauration = :restauration')
                ->setParameter('restauration', $territoireFilterDTO->getRestauration())
            ;
        }

        return $qb;
    }

    private function filterByGreenSpace(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        if (null !== $territoireFilterDTO->getGreenSpace()) {
            $qb->andWhere('u.green_space = :greenSpace')
                ->setParameter('greenSpace', $territoireFilterDTO->getGreenSpace())
            ;
        }

        return $qb;
    }

    private function filterByDateRange(QueryBuilder $qb, TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        if ($territoireFilterDTO->hasDateRange()) {
            $dateFormat = 'Y-m-d H:i:s';
            if (null !== $territoireFilterDTO->getFrom() && null !== $territoireFilterDTO->getTo()) {
                $qb->andWhere('r.created_at BETWEEN :from AND :to')
                    ->setParameter('from', $territoireFilterDTO->getFrom()->format($dateFormat))
                    ->setParameter('to', $territoireFilterDTO->getTo()->format($dateFormat))
                ;
            } elseif (null !== $territoireFilterDTO->getFrom() && null === $territoireFilterDTO->getTo()) {
                $qb->andWhere('r.created_at >= :from')
                    ->setParameter('from', $territoireFilterDTO->getFrom()->format($dateFormat))
                ;
            } elseif (null === $territoireFilterDTO->getFrom() && null !== $territoireFilterDTO->getTo()) {
                $qb->andWhere('r.created_at <= :to')
                    ->setParameter('to', $territoireFilterDTO->getTo()->format($dateFormat))
                ;
            }
        }

        return $qb;
    }
}
