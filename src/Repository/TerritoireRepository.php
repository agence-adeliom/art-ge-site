<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Territoire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<Territoire>
 *
 * @method Territoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Territoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Territoire[]    findAll()
 * @method Territoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerritoireRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Territoire::class);
    }

    public function getOneByUuid(string $uuid): ?Territoire
    {
        $uuid = Ulid::fromString($uuid)->toBinary();

        return $this->createQueryBuilder('t')
            ->andWhere('t.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getOneBySlug(string $slug): ?Territoire
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function loadUserByIdentifier(string $slug): ?UserInterface
    {
        return $this->getOneBySlug($slug);
    }
}
