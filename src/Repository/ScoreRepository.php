<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DashboardFilterDTO;
use App\Dto\TerritoireFilterDTO;
use App\Entity\Score;
use App\Enum\PilierEnum;
use App\Traits\RepositoryFilterTrait;
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
    use RepositoryFilterTrait;

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

    private function getPercentagesByThematiquesQuery(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->select('ROUND(AVG(s.points)) as avg_points')
            ->addSelect('ROUND(AVG(s.total)) as avg_total')
            ->addSelect('ROUND((SUM(s.points) / SUM(s.total)) * 100) as score')
            ->addSelect('th.name as name')
            ->addSelect('th.slug as slug')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 't')
            ->innerJoin('s.thematique', 'th')
            ->groupBy('s.thematique')
        ;

        return $this->addFilters($qb, $filterDTO);
    }

    public function getPercentagesByThematiques(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        return $this->getPercentagesByThematiquesQuery($filterDTO)->getQuery()->getArrayResult();
    }

    /**
     * @return array<mixed>
     */
    public function getPercentagesByTypologiesAndThematiques(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        $percentagesByTypologiseAndThematiques = [];

        foreach ($filterDTO->getTypologies() ?? [] as $typology) {
            $qb = $this->getPercentagesByThematiquesQuery($filterDTO);
            $qb->andWhere('t.slug = :typology')
                ->setParameter('typology', $typology)
            ;
            $percentagesByTypologiseAndThematiques[$typology] = $qb->getQuery()->getArrayResult();
        }

        return $percentagesByTypologiseAndThematiques;
    }

    /**
     * @return array<string, int>
     */
    public function getPercentagesByPiliersGlobal(): array
    {
        // TODO Make dynamic given the filters
        $percentagesByPiliers = [];

        foreach (PilierEnum::cases() as $pilier) {
            $thematiques = PilierEnum::getThematiquesSlugsByPilier($pilier);

            $sql = 'SELECT ROUND(AVG(temp.percentage)) FROM (';
            $sqlUnion = array_fill(0, count($thematiques), 'SELECT ROUND((SUM(S.points) / SUM(S.total)) * 100) as percentage FROM `score` S INNER JOIN thematique TH ON S.thematique_id = TH.id WHERE TH.slug = ?');
            $sql .= implode(' UNION ', $sqlUnion) . ') as temp';

            $percentagesByPiliers[$pilier->value] = (int) $this->getEntityManager()->getConnection()->executeQuery($sql, array_column($thematiques, 'value'))->fetchOne();
        }

        return $percentagesByPiliers;
    }
}
