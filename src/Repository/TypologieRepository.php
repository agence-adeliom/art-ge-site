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

    /**
     * @return array<mixed> Returns an array of RepondantTypologie slug
     */
    public function getSlugs(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.slug')
            ->getQuery()
            ->getSingleColumnResult()
        ;
    }

    /**
     * @return array<mixed> Returns an array of RepondantTypologie slug and name
     */
    public function getSlugsAndNames(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.slug, t.name')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
