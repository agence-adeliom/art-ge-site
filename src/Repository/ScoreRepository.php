<?php

declare(strict_types=1);

namespace App\Repository;

use App\Controller\Api\DashboardDataController;
use App\Dto\DashboardFilterDTO;
use App\Dto\TerritoireFilterDTO;
use App\Entity\Score;
use App\Enum\DepartementEnum;
use App\Enum\PilierEnum;
use App\Enum\TerritoireAreaEnum;
use App\Traits\RepositoryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 *
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    use RepositoryFilterTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    private function getPercentagesByThematiquesQuery(DashboardFilterDTO | TerritoireFilterDTO $filterDTO, array $reponsesIds = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->select('ROUND((SUM(s.points) / SUM(s.total)) * 100) as score')
            ->addSelect('th.name as name')
            ->addSelect('th.slug as slug')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->innerJoin('s.thematique', 'th')
            ->groupBy('s.thematique')
        ;

        if ([] !== $reponsesIds) {
            $qb
                ->andWhere('r.id IN (:reponsesIds)')
                ->setParameter('reponsesIds', $reponsesIds)
            ;
        }

        return $this->addFilters($qb, $filterDTO);
    }

    public function getPercentagesByThematiques(DashboardFilterDTO | TerritoireFilterDTO $filterDTO, array $reponsesIds = []): array
    {
        return $this->getPercentagesByThematiquesQuery($filterDTO, $reponsesIds)->getQuery()->getArrayResult();
    }

    /**
     * @return array<string, int>
     */
    public function getPercentagesByPiliersGlobal(DashboardFilterDTO | TerritoireFilterDTO $filterDTO, array $reponsesIds = []): array
    {
        $percentagesByPiliers = [];

        if ([] !== $filterDTO->getTerritoires()) {
            $territoires = DashboardDataController::excludeParentDepartmentIfOT($filterDTO->getTerritoire(), $filterDTO->getTerritoires());
        } else {
            $territoires = array_values(array_merge([$filterDTO->getTerritoire()], $filterDTO->getTerritoires()));
        }

        foreach (PilierEnum::cases() as $pilier) {
            $counter = 0;
            $dqls = [];
            $parameters = [];
            $thematiques = PilierEnum::getThematiquesSlugsByPilier($pilier);
            foreach ($thematiques as $thematique) {
                $dql = $this->createQueryBuilder('s')
                    ->select('SUM(s.points) as points, SUM(s.total) as total')
                    ->innerJoin('s.reponse', 'r')
                    ->innerJoin('s.thematique', 't')
                    ->innerJoin('r.repondant', 'u')
                    ->innerJoin('u.typologie', 'ty')
                    ->andWhere('t.slug = ?' . $counter++)
                ;
                $parameters[] = $thematique->value;

                if ([] !== $reponsesIds) {
                    $dql
                        ->andWhere('r.id IN (' . implode(',', $reponsesIds) . ')')
                    ;
                }


                if ([] === $reponsesIds) {
                    if ($filterDTO instanceof DashboardFilterDTO) {
                        if ([] !== $territoires) {
                            $ors = [];
                            foreach ($territoires as $territoire) {
                                if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
                                    if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                                        $department = DepartementEnum::tryFrom($territoire->getSlug());
                                        if ($department) {
                                            if (DepartementEnum::ALSACE === $department) {
                                                $ors[] = $dql->expr()->between('u.zip', '?' . $counter++, '?' . $counter++);
                                                $parameters[] = '67%';
                                                $parameters[] = '69%';
                                            } else {
                                                $ors[] = $dql->expr()->like('u.zip', '?' . $counter++);
                                                $parameters[] = DepartementEnum::getCode($department) . '%';
                                            }
                                        }
                                    } else {
                                        $ors[] = $dql->expr()->in('u.zip', implode(',', $territoire->getInsees()));
                                    }
                                }
                            }
                            if ([] !== $ors) {
                                $dql->andWhere($dql->expr()->orX(...$ors));
                            }
                        }
                    }

                    if (! empty($filterDTO->getTypologies())) {
                        $ors = [];
                        foreach ($filterDTO->getTypologies() as $typologie) {
                            $ors[] = $dql->expr()->eq('ty.slug', '?' . $counter++);
                            $parameters[] = $typologie;
                        }
                        $dql->andWhere($dql->expr()->orX(...$ors));
                    }

                    if ($filterDTO->hasDateRange()) {
                        $dateFormat = 'Y-m-d H:i:s';
                        if (null !== $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                            $dql->andWhere('r.submittedAt BETWEEN ?' . $counter++ . ' AND ?' . $counter++);
                            $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                            $parameters[] = $filterDTO->getTo()->format($dateFormat);
                        } elseif (null !== $filterDTO->getFrom() && null === $filterDTO->getTo()) {
                            $dql->andWhere('r.submittedAt >= ?' . $counter++);
                            $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                        } elseif (null === $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                            $dql->andWhere('r.submittedAt <= ?' . $counter++);
                            $parameters[] = $filterDTO->getTo()->format($dateFormat);
                        }
                    }
                }

                $dqls[] = $dql;
            }

            // le résultat est le produit en croix de toutes les sommes des points des thematiques divisées par les total des thematiques * 100
            $sql = 'SELECT ROUND((SUM(temp.sclr_0) / SUM(temp.sclr_1)) * 100) as percentage FROM ((';
            // pour chaque thematique on construit une requete sql

            /** @phpstan-ignore-next-line */
            $sqlUnion = array_map(fn (QueryBuilder $dql): string => (string) $dql->getQuery()->getSQL(), $dqls);

            $sql .= implode(') UNION (', $sqlUnion) . ')) as temp';
            $percentagesByPiliers[$pilier->value] = (int) $this->getEntityManager()->getConnection()->executeQuery($sql, $parameters)->fetchOne();
        }

        return $percentagesByPiliers;
    }
}
