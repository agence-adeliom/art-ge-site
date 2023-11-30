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
use App\Entity\Typologie;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
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

        $numberOfReponses = $this->getNumberOfReponses();

        $percentageGlobal = $this->getPercentageGlobal();

        $percentagesByThematiques = $this->getPercentagesByThematiques();
        $percentagesByThematiquesAndTypology = $this->getPercentagesByThematiquesAndTypology();

        $repondants = $this->getRepondants();
        $repondants = array_map(static fn (array $repondant): array => [...$repondant, ...['uuid' => Uuid::fromBinary($repondant['uuid'])->toBase32()]], $repondants);

        $children = [];
        if (in_array($this->territoire->getArea(), [TerritoireAreaEnum::DEPARTEMENT, TerritoireAreaEnum::REGION])) {
            $children = $this->territoire->getChildren();
        }

        return [
            'territoire' => $this->territoire,
            'children' => $children,
            'reponses' => [
                'repondants' => $repondants,
                'numberOfReponses' => $numberOfReponses,
            ],
            'scores' => [
                'percentageGlobal' => $percentageGlobal,
                'percentagesByThematiques' => $percentagesByThematiques,
                'percentagesByThematiquesAndTypology' => $percentagesByThematiquesAndTypology,
            ],
            'query' => [
                'typologies' => $typologies,
                'restauration' => $restauration,
                'greenSpace' => $greenSpace,
            ],
        ];
    }

    private function addFiltersToQueryBuilder(): void
    {
        if (!$this->qb) {
            return;
        }

        if ($this->territoire && TerritoireAreaEnum::REGION !== $this->territoire->getArea()) {
            $ors = [];
            if (TerritoireAreaEnum::DEPARTEMENT === $this->territoire->getArea()) {
                $department = DepartementEnum::tryFrom($this->territoire->getSlug());
                if ($department) {
                    $ors[] = $this->qb->expr()->like('U.zip', ':zip');
                    $this->qb->setParameter('zip', DepartementEnum::getCode($department) . '%');
                }
            } else {
                foreach ($this->territoire->getZips() as $key => $zip) {
                    $ors[] = $this->qb->expr()->eq('U.zip', ':zip' . $key);
                    $this->qb->setParameter('zip' . $key, $zip);
                }
            }
            $this->qb->andWhere($this->qb->expr()->or(...$ors));
        }

        if (!empty($this->thematiques)) {
            $ors = [];
            foreach ($this->thematiques as $key => $thematique) {
                $ors[] = $this->qb->expr()->eq('TH.slug', ':thematique' . $key);
                $this->qb->setParameter('thematique' . $key, $thematique);
            }
            $this->qb->andWhere($this->qb->expr()->or(...$ors));
        }

        if (!empty($this->typologies)) {
            $ors = [];
            foreach ($this->typologies as $key => $typologie) {
                $ors[] = $this->qb->expr()->eq('T.slug', ':typologie' . $key);
                $this->qb->setParameter('typologie' . $key, $typologie);
            }
            $this->qb->andWhere($this->qb->expr()->or(...$ors));
        }

        if (null !== $this->restauration) {
            $this->qb->andWhere('U.restauration = :restauration')
                ->setParameter('restauration', $this->restauration)
            ;
        }

        if (null !== $this->greenSpace) {
            $this->qb->andWhere('U.green_space = :greenSpace')
                ->setParameter('greenSpace', $this->greenSpace)
            ;
        }

        if (null !== $this->from || null !== $this->to) {
            $dateFormat = 'Y-m-d H:i:s';
            if (null !== $this->from && null !== $this->to) {
                $this->qb->andWhere('R.created_at BETWEEN :from AND :to')
                    ->setParameter('from', $this->from->format($dateFormat))
                    ->setParameter('to', $this->to->format($dateFormat))
                ;
            } elseif (null !== $this->from && null === $this->to) {
                $this->qb->andWhere('R.created_at >= :from')
                    ->setParameter('from', $this->from->format($dateFormat))
                ;
            } elseif (null === $this->from && null !== $this->to) {
                $this->qb->andWhere('R.created_at <= :to')
                    ->setParameter('to', $this->to->format($dateFormat))
                ;
            }
        }
    }

    private function getNumberOfReponses(): mixed
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb->addSelect('COUNT(R.id)')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
            ->innerJoin('U', 'typologie', 'T', 'T.id = U.typologie_id')
        ;

        $this->addFiltersToQueryBuilder();

        /* @phpstan-ignore-next-line */
        return $this->qb->executeQuery()->fetchOne();
    }

    private function getPercentageGlobal(): float
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $percentageGlobalQuery = $this->qb->select('ROUND(SUM(R.points) / SUM(R.total) * 100) as percentage')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
        ;
        if ($this->territoire) {
            foreach ($this->territoire->getZips() as $zip) {
                $this->qb->orWhere('U.zip = :zip')
                    ->setParameter('zip', $zip)
                ;
            }
        }

        return (int) $percentageGlobalQuery->executeQuery()->fetchOne();
    }

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
    private function getPercentagesByThematiquesAndTypology(): array
    {
        $percentagesByThematiquesAndTypology = [];

        if (null === $this->typologies || empty($this->typologies)) {
            $typologies = array_map(static fn (Typologie $typologie): string => $typologie->getSlug(), $this->typologieRepository->findAll());
        } else {
            $typologies = $this->typologies;
        }

        foreach ($typologies as $typology) {
            $this->qb = $this->getPercentagesByThematiquesQuery();
            $this->qb->orWhere('T.slug = :typology')
                ->setParameter('typology', $typology)
            ;
            $percentagesByThematiquesAndTypology[$typology] = $this->qb->executeQuery()->fetchAllAssociative();
        }

        return $percentagesByThematiquesAndTypology;
    }

    private function getRepondants(): mixed
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
}
