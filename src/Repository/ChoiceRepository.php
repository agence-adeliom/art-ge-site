<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Choice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Choice>
 *
 * @method Choice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choice[]    findAll()
 * @method Choice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Choice::class);
    }

    public function getSlugById(int $id): string
    {
        return (string) $this->createQueryBuilder('c')
            ->select('c.slug')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getNumberOfReponses(Choice $choice, array $reponsesIds = []): int
    {
        return (int) $this->getEntityManager()->getConnection()->executeQuery('
            SELECT COUNT(R.id) 
            FROM choice C 
            INNER JOIN reponse_choice RC ON RC.choice_id = C.id
            INNER JOIN reponse R ON R.id = RC.reponse_id
            WHERE C.id = :id AND R.id IN (' . implode(',', $reponsesIds) . ')', ['id' => $choice->getId()])->fetchOne();
    }
}
