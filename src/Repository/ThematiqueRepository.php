<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Thematique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thematique>
 *
 * @method Thematique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematique[]    findAll()
 * @method Thematique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematique::class);
    }

    public function getOneByQuestionId(int $questionId): ?Thematique
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.question', 'q')
            ->andWhere('q.id = :question')
            ->setParameter('question', $questionId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return array<Thematique>
     */
    public function getAllExceptLabel(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug != :label')
            ->setParameter('label', 'labels')
            ->getQuery()
            ->getResult()
        ;
    }
}
