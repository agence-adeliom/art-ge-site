<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @return string[] Returns an array of zip codes
     */
    public function getAllZipCodes(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.zip')
            ->getQuery()
            ->getSingleColumnResult()
        ;
    }

    /**
     * @return City[] Returns an array of City objects
     */
    public function getByZipCode(string $zip): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.zip LIKE :zip')
            ->setParameter('zip', $zip . '%')
            ->orderBy('c.name', 'ASC')
            ->groupBy('c.name')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<string> Returns an array of zip codes for autocompletion
     */
    public function getZipCompletion(string $zip): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.zip')
            ->andWhere('c.zip LIKE :zip')
            ->setParameter('zip', $zip . '%')
            ->orderBy('c.zip', 'ASC')
            ->groupBy('c.zip')
            ->getQuery()
            ->getSingleColumnResult()
        ;
    }
}
