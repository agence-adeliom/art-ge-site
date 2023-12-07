<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\TerritoireFilterDTO;
use App\Entity\Score;
use App\Enum\DepartementEnum;
use App\Enum\PilierEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 *
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    private function prepareScoresByThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 't')
            ->innerJoin('s.thematique', 'th')
            ->andWhere('th.slug = :slug')
            ->setParameter('slug', $slug)
        ;

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

    private function getAverageMeanPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('ROUND(AVG(s.points), 2)')
        ;
    }

    public function getAverageMeanPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getAverageMeanPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function getHighestPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('MAX(s.points)')
        ;
    }

    public function getHighestPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getHighestPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function getLowestPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('MIN(s.points)')
        ;
    }

    public function getLowestPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getLowestPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function getPercentagesByThematiquesQuery(TerritoireFilterDTO $territoireFilterDTO): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->select('ROUND(AVG(s.points)) as avg_points')
            ->addSelect('ROUND(AVG(s.total)) as avg_total')
            ->addSelect('ROUND((SUM(s.points) / SUM(s.total)) * 100) as value')
            ->addSelect('th.name as name')
            ->addSelect('th.slug as slug')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 't')
            ->innerJoin('s.thematique', 'th')
            ->groupBy('s.thematique')
        ;

        return $this->addFiltersToQueryBuilder($qb, $territoireFilterDTO);
    }

    public function getPercentagesByThematiques(TerritoireFilterDTO $territoireFilterDTO): array
    {
        return $this->getPercentagesByThematiquesQuery($territoireFilterDTO)->getQuery()->getArrayResult();
    }

    /**
     * @return array<mixed>
     */
    public function getPercentagesByTypologiesAndThematiques(TerritoireFilterDTO $territoireFilterDTO): array
    {
        $percentagesByTypologiseAndThematiques = [];

        foreach ($territoireFilterDTO->getTypologies() ?? [] as $typology) {
            $qb = $this->getPercentagesByThematiquesQuery($territoireFilterDTO);
            $qb->andWhere('t.slug = :typology')
                ->setParameter('typology', $typology)
            ;
            $percentagesByTypologiseAndThematiques[$typology] = $qb->getQuery()->getArrayResult();
        }

        return $percentagesByTypologiseAndThematiques;
    }

    /**
     * @param array<mixed> $percentagesByTypologiseAndThematiques
     *
     * @return array<string, float>
     */
    public function getPercentagesByPiliersGlobal(array $percentagesByTypologiseAndThematiques): array
    {
        $percentagesByPiliers = [];

        foreach (PilierEnum::cases() as $key => $pilier) {
            $thematiques = PilierEnum::getThematiquesSlugsByPilier($pilier);

            $sql = 'SELECT ROUND(AVG(temp.percentage)) FROM (';
            $sqlUnion = [];
            foreach ($thematiques as $thematique) {
                $sqlUnion[] = 'SELECT ROUND((SUM(S.points) / SUM(S.total)) * 100) as percentage FROM `score` S INNER JOIN thematique TH ON S.thematique_id = TH.id WHERE TH.slug = ?';
            }
            $sql .= implode(' UNION ', $sqlUnion) . ') as temp';

            $percentagesByPiliers[$pilier->value] = $this->getEntityManager()->getConnection()->executeQuery($sql, array_column($thematiques, 'value'))->fetchOne();
        }

        return $percentagesByPiliers;
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
                $addJoin = true;
                $joins = $qb->getDQLPart('join');
                if ([] !== $joins) {
                    foreach ($qb->getDQLPart('join') as $key => $joins) {
                        if ('r' === $key || 's' === $key) {
                            foreach ($joins as $join) {
                                if ('r.repondant' === $join->getJoin()) {
                                    $addJoin = false;
                                }
                            }
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
            foreach ($this->thematiques as $key => $thematique) {
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
                    ->setParameter('to', $this->to->format($dateFormat))
                ;
            }
        }

        return $qb;
    }
}
