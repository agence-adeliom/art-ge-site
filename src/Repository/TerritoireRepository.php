<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DashboardFilterDTO;
use App\Dto\TerritoireFilterDTO;
use App\Entity\Territoire;
use App\Enum\DepartementEnum;
use App\Enum\PilierEnum;
use App\Enum\TerritoireAreaEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function getOneByUuidOrSlug(string $identifier): ?Territoire
    {
        $territoire = $this->getOneByUuid($identifier);
        if ($territoire) {
            return $territoire;
        }

        return $this->getOneBySlug($identifier);
    }

    public function getOneByUuid(string $uuid): ?Territoire
    {
        try {
            $uuid = Ulid::fromString($uuid)->toBinary();
        } catch (\InvalidArgumentException) {
            // $uuid is probably a slug so return null
            return null;
        }

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

    /**
     * @param array<string> $slug
     *
     * @return Territoire[]
     */
    public function getAllBySlugs(array $slugs): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug IN (:slugs)')
            ->setParameter('slugs', $slugs)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param array<string>|array<empty> $columns
     *
     * @return Territoire|array<mixed>|null ($columns is not empty ? array<mixed> : Territoire | null)
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllByType(TerritoireAreaEnum $territoireAreaEnum, array $columns = []): null | array | Territoire
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.area = :area')
            ->setParameter('area', $territoireAreaEnum->value)
        ;

        $qb = $this->selectOnlyColumns($columns, $qb, 't');

        return [] !== $columns ? $qb->getQuery()->getArrayResult() : $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param array<string>|array<empty> $columns
     *
     * @return Territoire|array<mixed>|null ($columns is not empty ? array<mixed> : Territoire | null)
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTourismsByLinkedTerritoire(Territoire $linkedTerritoire, array $columns = []): null | array | Territoire
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.tourismTerritoires', 'tt')
            ->andWhere('t.area = :area')
            ->andWhere('tt.id = :linkedId')
            ->setParameter('linkedId', $linkedTerritoire->getId())
            ->setParameter('area', TerritoireAreaEnum::TOURISME->value)
        ;

        $qb = $this->selectOnlyColumns($columns, $qb, 'tt');

        return [] !== $columns ? $qb->getQuery()->getArrayResult() : $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param array<string>              $departments
     * @param array<string>|array<empty> $columns
     *
     * @return Territoire[]|array<mixed> ($columns is not empty ? array<mixed> : Territoire[])
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOTsByDepartments(array $departments, array $columns = []): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.territoiresChildren', 'tc')
            ->andWhere('t.area = :area')
            ->andWhere('t.slug IN (:departments)')
            ->setParameter('departments', $departments)
            ->setParameter('area', TerritoireAreaEnum::DEPARTEMENT->value)
        ;

        $qb = $this->selectOnlyColumns($columns, $qb, 'tc');

        return [] !== $columns ? $qb->getQuery()->getArrayResult() : $qb->getQuery()->getResult();
    }

    public function getPercentagesByDepartment(Territoire $territoire): int
    {
        $department = DepartementEnum::from($territoire->getSlug());
        $departmentCode = DepartementEnum::getCode($department);
        $sql = "
        SELECT ROUND(SUM(temp.percentage) / SUM(temp.total) * 100) as percentage 
        FROM (
            SELECT U.id as `user`, U.zip, ROUND(SUM(R.points) / SUM(R.total) * 100) as percentage, SUM(R.total) as total 
            FROM reponse R 
            INNER JOIN repondant U ON R.repondant_id = U.id 
            GROUP BY U.id
        ) as temp 
        WHERE zip LIKE ?;
        ";

        return (int) $this->getEntityManager()->getConnection()->executeQuery($sql, [$departmentCode . '%'])->fetchOne();
    }

    /**
     * @param array<string>|array<empty> $columns
     */
    private function selectOnlyColumns(array $columns, QueryBuilder $qb, string $alias): QueryBuilder
    {
        if ([] !== $columns) {
            foreach ($columns as $key => $column) {
                if (0 === $key) {
                    /* @phpstan-ignore-next-line */
                    $qb->select($alias . '.' . $column);
                } else {
                    /* @phpstan-ignore-next-line */
                    $qb->addSelect($alias . '.' . $column);
                }
            }
        }

        return $qb;
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->getOneBySlug($identifier);
    }
}
