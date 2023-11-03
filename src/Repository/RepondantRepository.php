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

    public function getOneByEmail(string $email): ?Repondant
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
