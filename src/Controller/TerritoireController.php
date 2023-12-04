<?php

/**
 * NOMBRE DE REPONSES LIEES AUX FILTRES.
 *
 * SELECT COUNT(R.id) FROM reponse R
 * INNER JOIN repondant U ON R.repondant_id = U.id
 * INNER JOIN typologie T ON U.typologie_id = T.id
 * WHERE U.zip = '67000' AND T.slug IN ('hotel');
 * */

/*
 * SCORE GLOBAL DU TERRITOIRE.
 *
 * SELECT ROUND(SUM(R.points) / SUM(R.total) * 100) as percentage
 * FROM reponse R
 * INNER JOIN repondant U ON U.id = R.repondant_id
 * WHERE U.zip IN ('67000', '67500');
 * */

/*
 * SCORE GLOBAL DU TERRITOIRE PAR THEMATIQUE
 *
 * SELECT SUM(S.points) as avg_points, SUM(S.total) as avg_total, ROUND((SUM(S.points) / SUM(S.total)) * 100) as percentage FROM `score` S
 * INNER JOIN reponse R ON S.reponse_id = R.id
 * INNER JOIN repondant U ON R.repondant_id = U.id
 * WHERE U.zip = '67000'
 * GROUP BY thematique_id;
 * */

/*
 * SCORE GLOBAL DU TERRITOIRE PAR THEMATIQUE AND TYPOLOGIE
 *
 * SELECT SUM(S.points) as avg_points, SUM(S.total) as avg_total, ROUND((SUM(S.points) / SUM(S.total)) * 100) as percentage FROM `score` S
 * INNER JOIN reponse R ON S.reponse_id = R.id
 * INNER JOIN repondant U ON R.repondant_id = U.id
 * INNER JOIN typologie T ON U.typologie_id = T.id
 * WHERE U.zip = '67000' AND T.slug IN ('hotel')
 * GROUP BY thematique_id;
 * */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Territoire;
use App\Entity\Thematique;
use App\Entity\Typologie;
use App\Enum\DepartementEnum;
use App\Enum\PilierEnum;
use App\Enum\TerritoireAreaEnum;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class TerritoireController extends AbstractController
{
    private ?Territoire $territoire = null;

    private ?QueryBuilder $qb = null;

    /** @var array<string>|null */
    private ?array $thematiques = null;

    /** @var array<string>|null */
    private ?array $typologies = null;

    private ?bool $restauration = null;

    private ?bool $greenSpace = null;

    private ?\DateTimeImmutable $from = null;

    private ?\DateTimeImmutable $to = null;

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @param array<string>|null $typologies
     *
     * @return array<mixed>
     */
    #[Route('/territoire/{identifier}', name: 'app_territoire_single')]
    #[Template('territoire.html.twig')]
    public function __invoke(
        string $identifier,
        #[MapQueryParameter] ?array $typologies,
        #[MapQueryParameter] ?bool $restauration,
        #[MapQueryParameter(name: 'green_space')] ?bool $greenSpace,
        #[MapQueryParameter] ?string $from,
        #[MapQueryParameter] ?string $to,
    ): array {
        $this->territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$this->territoire) {
            throw new TerritoireNotFound();
        }

        $this->typologies = $typologies;
        $this->restauration = $restauration;
        $this->greenSpace = $greenSpace;
        $this->from = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) $from) ?: null;
        $this->to = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $to . ' 23:59:59') ?: null;

        if (null === $this->typologies || [] === $this->typologies) {
            $this->typologies = array_map(static fn (Typologie $typologie): string => $typologie->getSlug(), $this->typologieRepository->findAll());
        }

        if (null === $this->thematiques || [] === $this->thematiques) {
            $this->thematiques = array_map(static fn (Thematique $thematique): string => $thematique->getSlug(), $this->thematiqueRepository->getAllExceptLabel());
        }

        $numberOfReponsesGlobal = $this->getNumberOfReponsesGlobal(); // 29 BasRhin
        $numberOfReponsesRegionGlobal = $this->getNumberOfReponsesRegionGlobal(); // 300 région

        $percentageGlobal = $this->getPercentageGlobal(); // 58%
        $percentageRegionGlobal = $this->getPercentageRegionGlobal(); // 56%

        $repondantsGlobal = $this->getRepondantsGlobal();
        $repondantsByTypologieGlobal = $this->getRepondantsByTypologieGlobal();
        $repondantsGlobal = array_map(static fn (array $repondant): array => [...$repondant, ...['uuid' => Uuid::fromBinary($repondant['uuid'])->toBase32()]], $repondantsGlobal);

        $percentagesByThematiques = $this->getPercentagesByThematiques();
        $percentagesByThematiquesAndTypologies = $this->getPercentagesByThematiquesAndTypologies();
        $percentagesByTypologiesAndThematiques = $this->getPercentagesByTypologiesAndThematiques(); // internal use only
        $percentagesByTypology = $this->getPercentagesByTypology($percentagesByTypologiesAndThematiques);
        $percentagesByPiliersGlobal = $this->getPercentagesByPiliersGlobal($percentagesByTypologiesAndThematiques);

        $children = [];
        if (in_array($this->territoire->getArea(), [TerritoireAreaEnum::DEPARTEMENT, TerritoireAreaEnum::REGION])) {
            $children = $this->territoire->getChildren();
        }

        return [
            'territoire' => $this->territoire,
            'children' => $children,
            'reponses' => [
                'repondantsGlobal' => $repondantsGlobal,
                'repondantsByTypologieGlobal' => $repondantsByTypologieGlobal,
                'numberOfReponsesGlobal' => $numberOfReponsesGlobal,
                'numberOfReponsesRegionGlobal' => $numberOfReponsesRegionGlobal,
            ],
            'scores' => [
                'percentageGlobal' => $percentageGlobal,
                'percentageRegionGlobal' => $percentageRegionGlobal,
                'percentagesByPiliersGlobal' => $percentagesByPiliersGlobal,
                'percentagesByThematiques' => $percentagesByThematiques,
                'percentagesByTypology' => $percentagesByTypology,
                'percentagesByThematiquesAndTypologies' => $percentagesByThematiquesAndTypologies,
            ],
            'query' => [
                'typologies' => $typologies,
                'restauration' => $restauration,
                'greenSpace' => $greenSpace,
            ],
        ];
    }

    /**
     * GLOBAL.
     */
    private function getNumberOfReponsesGlobal(): int
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb->addSelect('COUNT(R.id)')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
            ->innerJoin('U', 'typologie', 'T', 'T.id = U.typologie_id')
        ;

        $this->addFiltersToQueryBuilder();

        return (int) $this->qb?->executeQuery()->fetchOne();
    }

    private function getNumberOfReponsesRegionGlobal(): int
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb->addSelect('COUNT(R.id)')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
            ->innerJoin('U', 'typologie', 'T', 'T.id = U.typologie_id')
        ;

        return (int) $this->qb->executeQuery()->fetchOne();
    }

    private function getPercentageGlobal(): float
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $percentageGlobalQuery = $this->qb->select('ROUND(SUM(R.points) / SUM(R.total) * 100) as percentage')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
        ;

        $this->filterByAreaZipCodes($this->qb);

        return (int) $percentageGlobalQuery->executeQuery()->fetchOne();
    }

    private function getPercentageRegionGlobal(): float
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $percentageGlobalQuery = $this->qb->select('ROUND(SUM(R.points) / SUM(R.total) * 100) as percentage')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
        ;

        return (int) $percentageGlobalQuery->executeQuery()->fetchOne();
    }

    private function getRepondantsGlobal(): mixed
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb
            ->select('T.name as typologie, R.uuid, U.company, MAX(R.points) as points, R.total')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
            ->innerJoin('U', 'typologie', 'T', 'T.id = U.typologie_id')
            ->groupBy('U.id')
        ;

        $this->addFiltersToQueryBuilder();

        /* @phpstan-ignore-next-line */
        return $this->qb->executeQuery()->fetchAllAssociative();
    }

    /**
     * @return array<mixed>
     */
    private function getRepondantsByTypologieGlobal(): array
    {
        $repondantsByTypologieGlobal = [];

        $zipCriteria = '';
        $zipParams = [];
        if ($this->territoire && TerritoireAreaEnum::REGION !== $this->territoire->getArea()) {
            if (TerritoireAreaEnum::DEPARTEMENT === $this->territoire->getArea()) {
                $department = DepartementEnum::tryFrom($this->territoire->getSlug());
                if ($department) {
                    $zipCriteria = 'AND U.zip LIKE :zip';
                    $zipParams = ['zip' => DepartementEnum::getCode($department) . '%'];
                }
            } else {
                $zips = implode(',', $this->territoire->getZips());
                $zipCriteria = 'AND U.zip IN (:zip)';
                $zipParams = ['zip' => $zips];
            }
        }

        foreach ($this->typologies ?? [] as $typology) {
            $repondantsByTypologieGlobal[$typology] = $this->entityManager->getConnection()->executeQuery('
            SELECT COUNT(id) FROM (
                SELECT U.id FROM reponse R 
                    INNER JOIN repondant U ON U.id = R.repondant_id 
                    INNER JOIN typologie T ON T.id = U.typologie_id 
                WHERE 
                    T.slug = :slug 
                    ' . $zipCriteria . '
                GROUP BY U.id
            ) as temp;
            ', ['slug' => $typology, ...$zipParams])->fetchOne();
        }

        return $repondantsByTypologieGlobal;
    }

    /**
     * DEPENDENT ON FILTERS.
     */
    private function getPercentagesByThematiquesQuery(): QueryBuilder
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb->addSelect('ROUND(AVG(S.points)) as avg_points')
            ->addSelect('ROUND(AVG(S.total)) as avg_total')
            ->addSelect('ROUND((SUM(S.points) / SUM(S.total)) * 100) as value')
            ->addSelect('TH.name as name')
            ->addSelect('TH.slug as slug')
            ->from('score', 'S')
            ->innerJoin('S', 'reponse', 'R', 'R.id = S.reponse_id')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
            ->innerJoin('U', 'typologie', 'T', 'T.id = U.typologie_id')
            ->innerJoin('S', 'thematique', 'TH', 'TH.id = S.thematique_id')
            ->groupBy('S.thematique_id')
        ;

        $this->addFiltersToQueryBuilder();

        /* @phpstan-ignore-next-line */
        return $this->qb;
    }

    /**
     * @return array<mixed>
     */
    private function getPercentagesByThematiques(): array
    {
        $this->qb = $this->getPercentagesByThematiquesQuery();

        return $this->qb->executeQuery()->fetchAllAssociative();
    }

    /**
     * @return array<mixed>
     */
    private function getPercentagesByThematiquesAndTypologies(): array
    {
        $thematiquesAndTypologies = [];
        foreach ($this->thematiques ?? [] as $key => $thematique) {
            foreach ($this->typologies ?? [] as $typology) {
                //                $this->qb = $this->getPercentagesByThematiquesQuery();
                //                $this->qb->andWhere('TH.slug = :thematique')
                //                    ->setParameter('thematique', $thematique)
                //                    ->andWhere('T.slug = :typology')
                //                    ->setParameter('typology', $typology)
                //                ;
                $thematiquesAndTypologies[$key][$typology] = [];
            }
        }

        return $thematiquesAndTypologies;
    }

    /**
     * @return array<mixed>
     */
    private function getPercentagesByTypologiesAndThematiques(): array
    {
        $percentagesByTypologiseAndThematiques = [];

        foreach ($this->typologies ?? [] as $typology) {
            $this->qb = $this->getPercentagesByThematiquesQuery();
            $this->qb->andWhere('T.slug = :typology')
                ->setParameter('typology', $typology)
            ;
            $percentagesByTypologiseAndThematiques[$typology] = $this->qb->executeQuery()->fetchAllAssociative();
        }

        return $percentagesByTypologiseAndThematiques;
    }

    /**
     * @param array<mixed> $percentagesByTypologiseAndThematiques
     *
     * @return array<string, float>
     */
    private function getPercentagesByTypology(array $percentagesByTypologiseAndThematiques): array
    {
        $percentagesByTypology = [];

        foreach ($percentagesByTypologiseAndThematiques as $typology => $scores) {
            $typologyPercentages = array_column($scores, 'value'); // la liste des pourcentages par thematique pour la typologie
            $percentagesByTypology[$typology] = round(array_sum($typologyPercentages) / count($typologyPercentages));
        }

        return $percentagesByTypology;
    }

    /**
     * @param array<mixed> $percentagesByTypologiseAndThematiques
     *
     * @return array<string, float>
     */
    private function getPercentagesByPiliersGlobal(array $percentagesByTypologiseAndThematiques): array
    {
        $percentagesByPiliers = [];

        foreach (PilierEnum::cases() as $key => $pilier) {
            $thematiques = PilierEnum::getThematiquesSlugsByPilier($pilier);

            $sql = 'SELECT ROUND(AVG(temp.percentage)) FROM (';
            $sqlUnion = [];
            foreach ($thematiques as $thematique) {
                $sqlUnion[] = 'SELECT ROUND((SUM(S.points) / SUM(S.total)) * 100) as percentage FROM `score` S INNER JOIN thematique TH ON S.thematique_id = TH.id WHERE TH.slug = ?';
            }
            $sql .= implode(' UNION ', $sqlUnion) . ') as temp';

            $percentagesByPiliers[$pilier->value] = $this->entityManager->getConnection()->executeQuery($sql, array_column($thematiques, 'value'))->fetchOne();;
        }

        return $percentagesByPiliers;
    }

    /**
     * FILTERS.
     */
    private function addFiltersToQueryBuilder(): void
    {
        if (!$this->qb) {
            return;
        }

        $this->filterByAreaZipCodes($this->qb);

        $this->filterByThematique($this->qb);

        $this->filterByTypology($this->qb);

        $this->filterByRestauration($this->qb);

        $this->filterByGreenSpace($this->qb);

        $this->filterByDateRange($this->qb);
    }

    private function filterByAreaZipCodes(QueryBuilder $qb): void
    {
        if ($this->territoire && TerritoireAreaEnum::REGION !== $this->territoire->getArea()) {
            $ors = [];
            if (TerritoireAreaEnum::DEPARTEMENT === $this->territoire->getArea()) {
                $department = DepartementEnum::tryFrom($this->territoire->getSlug());
                if ($department) {
                    $ors[] = $qb->expr()->like('U.zip', ':zip');
                    $qb->setParameter('zip', DepartementEnum::getCode($department) . '%');
                }
            } else {
                $zips = implode(',', $this->territoire->getZips());
                $ors[] = $qb->expr()->in('U.zip', ':zip');
                $qb->setParameter('zip', $zips);
            }
            if ([] !== $ors) {
                $qb->andWhere($qb->expr()->and(...$ors));
            }
        }
    }

    private function filterByThematique(?QueryBuilder $qb): void
    {
        if (!$qb) {
            return;
        }

        if (array_key_exists('TH', $qb->getQueryParts()['join'] ?? []) && !empty($this->thematiques)) {
            $ors = [];
            foreach ($this->thematiques as $key => $thematique) {
                $ors[] = $qb->expr()->eq('TH.slug', ':thematique' . $key);
                $qb->setParameter('thematique' . $key, $thematique);
            }
            $qb->andWhere($qb->expr()->or(...$ors));
        }
    }

    private function filterByTypology(?QueryBuilder $qb): void
    {
        if (!$qb) {
            return;
        }

        if (!empty($this->typologies)) {
            $ors = [];
            foreach ($this->typologies as $key => $typologie) {
                $ors[] = $qb->expr()->eq('T.slug', ':typologie' . $key);
                $qb->setParameter('typologie' . $key, $typologie);
            }
            $qb->andWhere($qb->expr()->or(...$ors));
        }
    }

    private function filterByRestauration(?QueryBuilder $qb): void
    {
        if (!$qb) {
            return;
        }

        if (null !== $this->restauration) {
            $qb->andWhere('U.restauration = :restauration')
                ->setParameter('restauration', $this->restauration)
            ;
        }
    }

    private function filterByGreenSpace(?QueryBuilder $qb): void
    {
        if (!$qb) {
            return;
        }
        if (null !== $this->greenSpace) {
            $qb->andWhere('U.green_space = :greenSpace')
                ->setParameter('greenSpace', $this->greenSpace)
            ;
        }
    }

    private function filterByDateRange(?QueryBuilder $qb): void
    {
        if (!$qb) {
            return;
        }

        if (null !== $this->from || null !== $this->to) {
            $dateFormat = 'Y-m-d H:i:s';
            if (null !== $this->from && null !== $this->to) {
                $qb->andWhere('R.created_at BETWEEN :from AND :to')
                    ->setParameter('from', $this->from->format($dateFormat))
                    ->setParameter('to', $this->to->format($dateFormat))
                ;
            } elseif (null !== $this->from && null === $this->to) {
                /* @phpstan-ignore-next-line */
                $this->qb->andWhere('R.created_at >= :from')
                    ->setParameter('from', $this->from->format($dateFormat))
                ;
            } elseif (null === $this->from && null !== $this->to) {
                /* @phpstan-ignore-next-line */
                $this->qb->andWhere('R.created_at <= :to')
                    ->setParameter('to', $this->to->format($dateFormat))
                ;
            }
        }
    }
}
