<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reponse;
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
    public function getAverageMeanPointsOfAllReponses(?bool $restauration, ?bool $greenSpace): float
    {
        return (float) $this->getAverageMeanPointsQB($restauration, $greenSpace)
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

    public function getAverageMeanPointsOfDepartment(string $slug, ?bool $restauration, ?bool $greenSpace): float
    {
        $qb = $this->getAverageMeanPointsQB($restauration, $greenSpace);
        $qb = $this->joinByDepartment($qb, $slug);

        return (float) $qb
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

    public function getAverageMeanPointsOfTypologie(string $slug, ?bool $restauration, ?bool $greenSpace): float
    {
        $qb = $this->getAverageMeanPointsQB($restauration, $greenSpace);
        $qb = $this->joinByTypologie($qb, $slug);

        return (float) $qb
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
}
