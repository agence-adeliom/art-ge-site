<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Typologie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typologie>
 *
 * @method Typologie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typologie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typologie[]    findAll()
 * @method Typologie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypologieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typologie::class);
    }

    //    /**
    //     * @return RepondantTypologie[] Returns an array of RepondantTypologie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RepondantTypologie
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
