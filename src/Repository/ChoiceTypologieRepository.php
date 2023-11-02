<?php

namespace App\Repository;

use App\Entity\ChoiceTypologie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChoiceTypologie>
 *
 * @method ChoiceTypologie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChoiceTypologie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChoiceTypologie[]    findAll()
 * @method ChoiceTypologie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoiceTypologieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChoiceTypologie::class);
    }

//    /**
//     * @return ChoiceRepondantTypologie[] Returns an array of ChoiceRepondantTypologie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChoiceRepondantTypologie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
