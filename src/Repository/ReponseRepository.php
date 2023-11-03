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

    public function getAverageMeanPointsQB(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('ROUND(AVG(r.points) / AVG(r.total) * 100, 2)');
    }

    public function getHighestPointsQB(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('MAX(r.points)');
    }

    public function getLowestPointsQB(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('MIN(r.points)');
    }

    /** GLOBAL */

    public function getAverageMeanPointsOfAllReponses(): float
    {
        return (float) $this->getAverageMeanPointsQB()
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getHighestPointsOfAllReponses(): int
    {
        return (int) $this->getHighestPointsQB()
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLowestPointsOfAllReponses(): int
    {
        return (int) $this->getLowestPointsQB()
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** BY DEPARTMENT */

    private function joinByDepartment(QueryBuilder $qb, string $slug): QueryBuilder
    {
        return $qb
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.department', 'd')
            ->andWhere('d.slug = :slug')
            ->setParameter('slug', $slug);
    }

    public function getAverageMeanPointsOfDepartment(string $slug): float
    {
        $qb = $this->getAverageMeanPointsQB();
        $qb = $this->joinByDepartment($qb, $slug);

        return (float) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getHighestPointsOfDepartment(string $slug): int
    {
        $qb = $this->getHighestPointsQB();
        $qb = $this->joinByDepartment($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLowestPointsOfDepartment(string $slug): int
    {
        $qb = $this->getLowestPointsQB();
        $qb = $this->joinByDepartment($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** BY TYPOLOGIE */

    private function joinByTypologie(QueryBuilder $qb, string $slug): QueryBuilder
    {
        return $qb
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug);
    }

    public function getAverageMeanPointsOfTypologie(string $slug): float
    {
        $qb = $this->getAverageMeanPointsQB();
        $qb = $this->joinByTypologie($qb, $slug);

        return (float) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getHighestPointsOfTypologie(string $slug): int
    {
        $qb = $this->getHighestPointsQB();
        $qb = $this->joinByTypologie($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLowestPointsOfTypologie(string $slug): int
    {
        $qb = $this->getLowestPointsQB();
        $qb = $this->joinByTypologie($qb, $slug);

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
