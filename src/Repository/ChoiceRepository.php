<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DashboardFilterDTO;
use App\Entity\Choice;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
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

    public function getNumberOfReponses(Choice $choice, DashboardFilterDTO $dashboardFilterDTO): int
    {
        $zipCriteria = '';
        $zipParams = [];
        $territoire = $dashboardFilterDTO->getTerritoire();
        if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
            if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                $department = DepartementEnum::tryFrom($territoire->getSlug());
                if ($department) {
                    if (DepartementEnum::ALSACE === $department) {
                        $zipCriteria = 'AND U.zip BETWEEN :zip67 AND :zip69';
                        $zipParams = ['zip67' => '67%', 'zip69' => '69%'];
                    } else {
                        $zipCriteria = 'AND U.zip LIKE :zip';
                        $zipParams = ['zip' => DepartementEnum::getCode($department) . '%'];
                    }
                }
            } else {
                $zipCriteria = 'AND U.zip IN (:zip)';
                $zipParams = ['zip' => $territoire->getZips()];
            }
        }

        $typologyCriteria = '';
        $typologyParams = [];
        if ([] !== $dashboardFilterDTO->getTypologies()) {
            $typologyCriteriaTemp = [];
            $typologyCriteria = 'AND (';
            foreach ($dashboardFilterDTO->getTypologies() as $key => $typology) {
                $typologyCriteriaTemp[] = 'TY.slug = :typology' . $key;
                $typologyParams['typology' . $key] = $typology;
            }
            $typologyCriteria .= implode(' OR ', $typologyCriteriaTemp) . ') ';
        }

        return (int) $this->getEntityManager()->getConnection()->executeQuery('
            SELECT COUNT(U.id) 
            FROM choice C 
            INNER JOIN reponse_choice RC ON RC.choice_id = C.id
            INNER JOIN reponse R ON R.id = RC.reponse_id
            INNER JOIN repondant U ON U.id = R.repondant_id 
            INNER JOIN typologie TY ON TY.id = U.typologie_id 
            WHERE C.id = :id
                    ' . $typologyCriteria . '
                    ' . $zipCriteria, [...['id' => $choice->getId()], ...$typologyParams, ...$zipParams])->fetchOne();
    }
}
