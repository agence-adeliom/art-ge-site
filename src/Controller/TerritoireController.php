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
 * SELECT ROUND(SUM(R.points) / SUM(R.total) * 100, 2) as percentage
 * FROM reponse R
 * INNER JOIN repondant U ON U.id = R.repondant_id
 * WHERE U.zip IN ('67000', '67500');
 * */

/*
 * SCORE GLOBAL DU TERRITOIRE PAR THEMATIQUE
 *
 * SELECT SUM(S.points) as avg_points, SUM(S.total) as avg_total, ROUND((SUM(S.points) / SUM(S.total)) * 100, 2) as percentage FROM `score` S
 * INNER JOIN reponse R ON S.reponse_id = R.id
 * INNER JOIN repondant U ON R.repondant_id = U.id
 * WHERE U.zip = '67000'
 * GROUP BY thematique_id;
 * */

/*
 * SCORE GLOBAL DU TERRITOIRE PAR THEMATIQUE AND TYPOLOGIE
 *
 * SELECT SUM(S.points) as avg_points, SUM(S.total) as avg_total, ROUND((SUM(S.points) / SUM(S.total)) * 100, 2) as percentage FROM `score` S
 * INNER JOIN reponse R ON S.reponse_id = R.id
 * INNER JOIN repondant U ON R.repondant_id = U.id
 * INNER JOIN typologie T ON U.typologie_id = T.id
 * WHERE U.zip = '67000' AND T.slug IN ('hotel')
 * GROUP BY thematique_id;
 * */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Territoire;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @param array<string>|null $thematiques
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
    ): array {
        $this->territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$this->territoire) {
            throw new TerritoireNotFound();
        }

        $this->typologies = $typologies;
        $this->restauration = $restauration;
        $this->greenSpace = $greenSpace;

        $numberOfReponses = $this->getNumberOfReponses();

        $percentageGlobal = $this->getPercentageGlobal();

        $percentages = $this->getPercentages();

        return [
            'territoire' => $this->territoire,
            'numberOfReponses' => $numberOfReponses,
            'percentageGlobal' => $percentageGlobal,
            'percentages' => $percentages,
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

        if ($this->territoire) {
            $ors = [];
            foreach ($this->territoire->getZips() as $key => $zip) {
                $ors[] = $this->qb->expr()->eq('U.zip', ':zip' . $key);
                $this->qb->setParameter('zip' . $key, $zip);
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
        $percentageGlobalQuery = $this->qb->select('ROUND(SUM(R.points) / SUM(R.total) * 100, 2) as percentage')
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

        return (float) $percentageGlobalQuery->executeQuery()->fetchOne();
    }

    /**
     * @return array<mixed>
     */
    private function getPercentages(): array
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder();
        $this->qb->addSelect('ROUND(AVG(S.points)) as avg_points')
            ->addSelect('ROUND(AVG(S.total)) as avg_total')
            ->addSelect('ROUND((SUM(S.points) / SUM(S.total)) * 100, 2) as value')
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
        return $this->qb->executeQuery()->fetchAllAssociative();
    }
}
