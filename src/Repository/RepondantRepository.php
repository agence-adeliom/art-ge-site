<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Repondant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repondant>
 *
 * @method Repondant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repondant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repondant[]    findAll()
 * @method Repondant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepondantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repondant::class);
    }

    //    /**
    //     * @return Repondant[] Returns an array of Repondant objects
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

    //    public function findOneBySomeField($value): ?Repondant
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
