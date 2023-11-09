<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Score;
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
}
