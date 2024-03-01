<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DashboardFilterDTO;
use App\Dto\TerritoireFilterDTO;
use App\Entity\Reponse;
use App\Traits\RepositoryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    use RepositoryFilterTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    public function getNumberOfReponsesGlobal(DashboardFilterDTO | TerritoireFilterDTO $filterDTO, array $reponsesIds = []): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id, MAX(r.submittedAt) as HIDDEN submittedAt')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->groupBy('u.id')
        ;

        $qb = $this->addFilters($qb, $filterDTO);

        if ([] !== $reponsesIds) {
            $qb
                ->andWhere('r.id IN (:reponsesIds)')
                ->setParameter('reponsesIds', $reponsesIds)
            ;
        }

        return count($qb->getQuery()->getSingleColumnResult());
    }

    public function getRepondantsGlobal(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('ty.name as typologie, r.uuid, u.company, u.city, MAX(r.points) as points, r.total')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->groupBy('u.id')
        ;

        $qb = $this->addFilters($qb, $filterDTO);

        return $qb
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getLastSubmissionDate(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): string
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.submittedAt')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->orderBy('r.submittedAt', 'DESC')
            ->setMaxResults(1)
        ;

        $qb = $this->addFilters($qb, $filterDTO);

        return $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getPercentageGlobal(DashboardFilterDTO | TerritoireFilterDTO $filterDTO, array $reponsesIds = []): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('ROUND(SUM(r.points) / SUM(r.total) * 100) as percentage')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
        ;

        $qb = $this->addFilters($qb, $filterDTO);

        if ([] !== $reponsesIds) {
            $qb
                ->andWhere('r.id IN (:reponsesIds)')
                ->setParameter('reponsesIds', $reponsesIds)
            ;
        }

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getPercentagesByTypology(string $typology, array $reponsesIds = []): ?int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('ROUND(SUM(r.points) / SUM(r.total) * 100) as percentage')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->andWhere('ty.slug = :typology')
            ->setParameter('typology', $typology)
        ;

        if ([] !== $reponsesIds) {
            $qb
                ->andWhere('r.id IN (:reponsesIds)')
                ->setParameter('reponsesIds', $reponsesIds)
            ;
        }

        $result = $qb
            ->getQuery()
            ->getSingleScalarResult();

        return null !== $result ? (int) $result : null;
    }
}
