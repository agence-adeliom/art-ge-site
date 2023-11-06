<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ChoiceTypologie;
use App\ValueObject\RepondantTypologie;
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
    public function getPonderation(int $choice, RepondantTypologie $rt): int
    {
        return (int) $this->createQueryBuilder('crt')
            ->select('crt.ponderation')
            ->andWhere('crt.choice = :choice')
            ->andWhere('crt.typologie = :typologie')
            ->andWhere('crt.restauration = :restauration')
            ->setParameter(':choice', $choice)
            ->setParameter(':typologie', $rt->getTypologie())
            ->setParameter(':restauration', $rt->getRestauration())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getTotalBasedOnTypologie(RepondantTypologie $rt): int
    {
        return (int) $this->createQueryBuilder('crt')
            ->select('SUM(crt.ponderation) as total')
            ->andWhere('crt.typologie = :typologie')
            ->andWhere('crt.restauration = :restauration')
            ->setParameter(':typologie', $rt->getTypologie())
            ->setParameter(':restauration', $rt->getRestauration())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getPonderationByQuestionAndTypologie(int $questionId, RepondantTypologie $rt): int
    {
        return (int) $this->createQueryBuilder('crt')
            ->select('SUM(crt.ponderation) as total')
            ->innerJoin('crt.choice', 'c')
            ->innerJoin('c.question', 'q')
            ->andWhere('q.id = :questionId')
            ->andWhere('crt.typologie = :typologie')
            ->andWhere('crt.restauration = :restauration')
            ->setParameter(':questionId', $questionId)
            ->setParameter(':typologie', $rt->getTypologie())
            ->setParameter(':restauration', $rt->getRestauration())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
