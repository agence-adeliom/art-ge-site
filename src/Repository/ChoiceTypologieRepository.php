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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getPonderation(int $choice, int $typologie, int $restauration, int $greenSpace): int
    {
        return $this->createQueryBuilder('crt')
            ->select('crt.ponderation')
            ->andWhere('crt.choice = :choice')
            ->andWhere('crt.typologie = :typologie')
            ->andWhere('crt.restauration = :restauration')
            ->andWhere('crt.greenSpace = :greenSpace')
            ->setParameter(':choice', $choice)
            ->setParameter(':typologie', $typologie)
            ->setParameter(':restauration', $restauration)
            ->setParameter(':greenSpace', $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getTotalBasedOnTypologie(int $typologie, int $restauration, int $greenSpace): int
    {
        return $this->createQueryBuilder('crt')
            ->select('SUM(crt.ponderation) as total')
            ->andWhere('crt.typologie = :typologie')
            ->andWhere('crt.restauration = :restauration')
            ->andWhere('crt.greenSpace = :greenSpace')
            ->setParameter(':typologie', $typologie)
            ->setParameter(':restauration', $restauration)
            ->setParameter(':greenSpace', $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
