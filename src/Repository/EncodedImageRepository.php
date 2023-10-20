<?php

namespace App\Repository;

use App\Entity\EncodedImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EncodedImage>
 *
 * @method EncodedImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncodedImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncodedImage[]    findAll()
 * @method EncodedImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncodedImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EncodedImage::class);
    }

//    /**
//     * @return EncodedImage[] Returns an array of EncodedImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EncodedImage
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
