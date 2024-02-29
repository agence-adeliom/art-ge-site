<?php

declare(strict_types=1);

namespace App\Repository;

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

    private function getPercentagesByThematiquesQuery(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->select('ROUND(AVG(s.points)) as avg_points')
            ->addSelect('ROUND(AVG(s.total)) as avg_total')
            ->addSelect('ROUND((SUM(s.points) / SUM(s.total)) * 100) as score')
            ->addSelect('th.name as name')
            ->addSelect('th.slug as slug')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->innerJoin('s.thematique', 'th')
            ->groupBy('s.thematique')
        ;

        return $this->addFilters($qb, $filterDTO);
    }

    public function getPercentagesByThematiques(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        return $this->getPercentagesByThematiquesQuery($filterDTO)->getQuery()->getArrayResult();
    }

    /**
     * Pour chaque pilier on va faire une requete SQL qui va nous permettre de compter le nombre de repondants
     * par thematique, et a la fin on fait la moyenne de toutes ces sommes pour avoir la valeur par pilier
     * La recherche par pilier est faite via une UNION sql des thematique.
     * La requete est construite dans la boucle foreach des thematiques, et chaque fois qu'on rencontre un ?
     * le parametres est ajoute au tableau des parametres.
     * A la fin on converti le DQL en SQL et on joint le tout avec une UNION.
     *
     * @return array<string, int>
     */
    public function getPercentagesByPiliersGlobal(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        $percentagesByPiliers = [];

        foreach (PilierEnum::cases() as $pilier) {
            $counter = 0;
            $dqls = [];
            $parameters = [];
            $thematiques = PilierEnum::getThematiquesSlugsByPilier($pilier);
            foreach ($thematiques as $thematique) {
                $parameters2 = [];
                $dql = $this->createQueryBuilder('s')
                    ->select('SUM(s.points) as points, SUM(s.total) as total')
                    ->innerJoin('s.reponse', 'r')
                    ->innerJoin('s.thematique', 't')
                    ->innerJoin('r.repondant', 'u')
                    ->innerJoin('u.typologie', 'ty')
                    ->groupBy('u.id')
                    ->andWhere('t.slug = ?' . $counter++)
                ;
                $parameters[] = $thematique->value;

                if ($filterDTO instanceof DashboardFilterDTO) {
                    $territoires = $filterDTO->getTerritoires() ?: [$filterDTO->getTerritoire()];
                    if ([] !== $territoires) {
                        foreach ($territoires as $territoire) {
                            if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
                                $ors = [];
                                if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                                    $department = DepartementEnum::tryFrom($territoire->getSlug());
                                    if ($department) {
                                        $ors[] = $dql->expr()->like('u.zip', '?' . $counter++);
                                        $parameters[] = DepartementEnum::getCode($department) . '%';
                                        $parameters2[] = DepartementEnum::getCode($department) . '%';
                                    }
                                } else {
                                    $ors[] = $dql->expr()->in('u.zip', implode(',', $territoire->getZips()));
                                }
                                if ([] !== $ors) {
                                    $dql->andWhere($dql->expr()->andX(...$ors));
                                }
                            }
                        }
                    }
                }

                if (!empty($filterDTO->getTypologies())) {
                    $ors = [];
                    foreach ($filterDTO->getTypologies() as $typologie) {
                        $ors[] = $dql->expr()->eq('ty.slug', '?' . $counter++);
                        $parameters[] = $typologie;
                        $parameters2[] = $typologie;
                    }
                    $dql->andWhere($dql->expr()->orX(...$ors));
                }

                if ($filterDTO->hasDateRange()) {
                    $dateFormat = 'Y-m-d H:i:s';
                    if (null !== $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt BETWEEN ?' . $counter++ . ' AND ?' . $counter++);
                        $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                        $parameters2[] = $filterDTO->getFrom()->format($dateFormat);
                        $parameters[] = $filterDTO->getTo()->format($dateFormat);
                        $parameters2[] = $filterDTO->getTo()->format($dateFormat);
                    } elseif (null !== $filterDTO->getFrom() && null === $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt >= ?' . $counter++);
                        $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                        $parameters2[] = $filterDTO->getFrom()->format($dateFormat);
                    } elseif (null === $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt <= ?' . $counter++);
                        $parameters[] = $filterDTO->getTo()->format($dateFormat);
                        $parameters2[] = $filterDTO->getTo()->format($dateFormat);
                    }
                }

                $dqls[] = $dql;
                $parameters = array_merge($parameters, $parameters2);
            }

            // le résultat est le produit en croix de toutes les sommes des points des thematiques divisées par les total des thematiques * 100
            $sql = 'SELECT ROUND((SUM(temp.sclr_0) / SUM(temp.sclr_1)) * 100) as percentage FROM ((';
            // pour chaque thematique on construit une requete sql
            $sqlUnion = array_map(fn (QueryBuilder $dql): string => (string) $dql->getQuery()->getSQL(), $dqls);

            //On construit la requete interne qui va aller chercher tous les reponses ID en utilisant les memes filtres
            /**
             * On récupère l'occurence de t2_.slug jusqu'à GROUP et on va reprendre ce filtre WHERE pour l'insérer
             * dans notre sous-query pour qu'on récupère les réponses avec les mêmes filtres
             *
             * Ca permet de transformer:
             *
             * SELECT SUM(s0_.points) AS sclr_0, SUM(s0_.total) AS sclr_1 FROM score s0_
             * INNER JOIN reponse r1_ ON s0_.reponse_id = r1_.id
             * INNER JOIN thematique t2_ ON s0_.thematique_id = t2_.id
             * INNER JOIN repondant r3_ ON r1_.repondant_id = r3_.id
             * INNER JOIN typologie t4_ ON r3_.typologie_id = t4_.id
             * WHERE t2_.slug = ? AND r3_.zip LIKE ? AND t4_.slug = ? AND (r1_.submitted_at BETWEEN ? AND ?)
             * GROUP BY r3_.id
             *
             * en ca :
             *
             * SELECT SUM(s0_.points) AS sclr_0, SUM(s0_.total) AS sclr_1 FROM score s0_
             * INNER JOIN reponse r1_ ON s0_.reponse_id = r1_.id
             * INNER JOIN thematique t2_ ON s0_.thematique_id = t2_.id
             * INNER JOIN repondant r3_ ON r1_.repondant_id = r3_.id
             * INNER JOIN typologie t4_ ON r3_.typologie_id = t4_.id
             * WHERE t2_.slug = ? AND r3_.zip LIKE ? AND t4_.slug = ? AND (r1_.submitted_at BETWEEN ? AND ?)
             *
             * AND r1_.id IN (
             *     SELECT reponse.id FROM reponse
             *     INNER JOIN repondant ON reponse.repondant_id = repondant.id
             *     INNER JOIN typologie ON repondant.typologie_id = typologie.id
             *     WHERE 1 = 1 AND r3_.zip LIKE ? AND t4_.slug = ? AND (r1_.submitted_at BETWEEN ? AND ?)
             * )
             * GROUP BY r3_.id
             *
             */
            foreach ($sqlUnion as $key => $uni) {
                $innerQuery = ' SELECT reponse.id FROM reponse INNER JOIN repondant ON reponse.repondant_id = repondant.id INNER JOIN typologie ON repondant.typologie_id = typologie.id WHERE 1 = 1';
                preg_match('#t2_\.slug.* GROUP#', $uni, $matches);
                $innerQuery .= str_replace('t2_.slug = ?', '', $matches[0]);
                $innerQuery = str_replace(' GROUP', '', $innerQuery);
                $innerQuery = ' AND r1_.id IN (' . $innerQuery . ') ';
                $sqlUnion[$key] = str_replace('GROUP BY ', $innerQuery . 'GROUP BY ', $uni);
            }

            $sql .= implode(') UNION (', $sqlUnion) . ')) as temp';
            $percentagesByPiliers[$pilier->value] = (int) $this->getEntityManager()->getConnection()->executeQuery($sql, $parameters)->fetchOne();
        }

        return $percentagesByPiliers;
    }
}
