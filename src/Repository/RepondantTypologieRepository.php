<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RepondantTypologie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepondantTypologie>
 *
 * @method RepondantTypologie|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepondantTypologie|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepondantTypologie[]    findAll()
 * @method RepondantTypologie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepondantTypologieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepondantTypologie::class);
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
