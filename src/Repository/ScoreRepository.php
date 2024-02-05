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

    private function prepareScoresByThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.reponse', 'r')
            ->innerJoin('r.repondant', 'u')
            ->innerJoin('u.typologie', 'ty')
            ->innerJoin('s.thematique', 'th')
            ->andWhere('th.slug = :slug')
            ->setParameter('slug', $slug)
        ;

        if (null !== $restauration) {
            $qb->andWhere('u.restauration = :restauration')
                ->setParameter('restauration', $restauration)
            ;
        }

        if (null !== $greenSpace) {
            $qb->andWhere('u.greenSpace = :greenSpace')
                ->setParameter('greenSpace', $greenSpace)
            ;
        }

        return $qb;
    }

    private function getAverageMeanPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('ROUND(AVG(s.points), 2)')
        ;
    }

    public function getAverageMeanPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getAverageMeanPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function getHighestPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('MAX(s.points)')
        ;
    }

    public function getHighestPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getHighestPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function getLowestPointsOfThematiqueQB(string $slug, ?bool $restauration, ?bool $greenSpace): QueryBuilder
    {
        return $this->prepareScoresByThematiqueQB($slug, $restauration, $greenSpace)
            ->select('MIN(s.points)')
        ;
    }

    public function getLowestPointsOfThematique(string $slug, ?bool $restauration, ?bool $greenSpace): int
    {
        return (int) $this->getLowestPointsOfThematiqueQB($slug, $restauration, $greenSpace)
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
     * @return array<mixed>
     */
    public function getPercentagesByTypologiesAndThematiques(DashboardFilterDTO | TerritoireFilterDTO $filterDTO): array
    {
        $percentagesByTypologiseAndThematiques = [];

        foreach ($filterDTO->getTypologies() ?? [] as $typology) {
            $qb = $this->getPercentagesByThematiquesQuery($filterDTO);
            $qb->andWhere('ty.slug = :typology')
                ->setParameter('typology', $typology)
            ;
            $percentagesByTypologiseAndThematiques[$typology] = $qb->getQuery()->getArrayResult();
        }

        return $percentagesByTypologiseAndThematiques;
    }

    /**
     * Pour chaque pilier on va faire une requete SQL qui va nous permettre de compter le nombre de repondants
     * par thematique, et a la fin on fait la moyenne de toutes ces sommes pour avoir la valeur par pilier
     * La recherche par pilier est faite via une UNION sql des thematique.
     * La requete est construite dans la boucle foreach des thematiques, et chaque fois qu'on rencontre un ?
     * le parametres est ajoute au tableau des parametres.
     * A la fin on converti le DQL en SQL et on joint le tout avec une UNION
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
                $dql = $this->createQueryBuilder('s')
                    ->select('SUM(s.points) as points, SUM(s.total) as total')
                    ->innerJoin('s.reponse', 'r')
                    ->innerJoin('s.thematique', 't')
                    ->innerJoin('r.repondant', 'u')
                    ->innerJoin('u.typologie', 'ty')
                    ->groupBy('u.id')
                    ->andWhere('t.slug = ?'.$counter++);
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
                                        $ors[] = $dql->expr()->like('u.zip', '?'.$counter++);
                                        $parameters[] = DepartementEnum::getCode($department) . '%';
                                    }
                                } else {
                                    $ors[] = $dql->expr()->in('u.zip', implode(',', $territoire->getZips()));
                                }
                                if ([] !== $ors) {
                                    $dql->orWhere($dql->expr()->andX(...$ors));
                                }
                            }

                        }
                    }
                }

                if (!empty($filterDTO->getTypologies())) {
                    $ors = [];
                    foreach ($filterDTO->getTypologies() as $typologie) {
                        $ors[] = $dql->expr()->eq('ty.slug', '?'.$counter++);
                        $parameters[] = $typologie;
                    }
                    $dql->andWhere($dql->expr()->orX(...$ors));
                }

                if ($filterDTO->hasDateRange()) {
                    $dateFormat = 'Y-m-d H:i:s';
                    if (null !== $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt BETWEEN ?'.$counter++.' AND ?'.$counter++);
                        $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                        $parameters[] = $filterDTO->getTo()->format($dateFormat);
                    } elseif (null !== $filterDTO->getFrom() && null === $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt >= ?'.$counter++);
                        $parameters[] = $filterDTO->getFrom()->format($dateFormat);
                    } elseif (null === $filterDTO->getFrom() && null !== $filterDTO->getTo()) {
                        $dql->andWhere('r.submittedAt <= ?'.$counter++);
                        $parameters[] = $filterDTO->getTo()->format($dateFormat);
                    }
                }

                $dqls[] = $dql;
            }

            // le résultat est le produit en croix de toutes les sommes des points des thematiques divisées par les total des thematiques * 100
            $sql = 'SELECT ROUND((SUM(temp.sclr_0) / SUM(temp.sclr_1)) * 100) as percentage FROM ((';
            // pour chaque thematique on construit une requete sql
            $sqlUnion = array_map(fn (QueryBuilder $dql): string => (string) $dql->getQuery()->getSQL(), $dqls);
            // on construit l'UNION des requetes
            $sql .= implode(') UNION (', $sqlUnion) . ')) as temp';
            $percentagesByPiliers[$pilier->value] = (int) $this->getEntityManager()->getConnection()->executeQuery($sql, $parameters)->fetchOne();
        }

        return $percentagesByPiliers;
    }
}
