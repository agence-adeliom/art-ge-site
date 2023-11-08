<?php

/**
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

use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class TerritoireController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @param array<string>|null $thematiques
     * @param array<string>|null $typologies
     */
    #[Route('/territoire/{identifier}', name: 'app_territoire_single')]
    public function __invoke(
        string $identifier,
        #[MapQueryParameter] ?array $thematiques,
        #[MapQueryParameter] ?array $typologies,
        #[MapQueryParameter] ?bool $restauration,
        #[MapQueryParameter(name: 'green_space')] ?bool $greenSpace,
    ): Response {
        $territoire = null;

        if ($identifier) {
            $territoire = $this->territoireRepository->getOneByUuid($identifier);
            if (!$territoire) {
                $territoire = $this->territoireRepository->getOneBySlug($identifier);
            }
        }

        if (!$territoire) {
            throw new TerritoireNotFound();
        }

        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $percentageGlobalQuery = $qb->select('ROUND(SUM(R.points) / SUM(R.total) * 100, 2) as percentage')
            ->from('reponse', 'R')
            ->innerJoin('R', 'repondant', 'U', 'U.id = R.repondant_id')
        ;
        foreach ($territoire->getZips() as $zip) {
            $qb->orWhere('U.zip = :zip')
                ->setParameter('zip', $zip)
            ;
        }

        $percentageGlobal = (float) $percentageGlobalQuery->executeQuery()->fetchOne();

        $qb = $this->entityManager->getConnection()->createQueryBuilder();
        $qb->addSelect('ROUND(AVG(S.points)) as avg_points')
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

        $ors = [];
        foreach ($territoire->getZips() as $key => $zip) {
            $ors[] = $qb->expr()->eq('U.zip', ':zip' . $key);
            $qb->setParameter('zip' . $key, $zip);
        }
        $qb->andWhere($qb->expr()->or(...$ors));

        if (!empty($thematiques)) {
            $ors = [];
            foreach ($thematiques as $key => $thematique) {
                $ors[] = $qb->expr()->eq('TH.slug', ':thematique' . $key);
                $qb->setParameter('thematique' . $key, $thematique);
            }
            $qb->andWhere($qb->expr()->or(...$ors));
        }

        if (!empty($typologies)) {
            $ors = [];
            foreach ($typologies as $key => $typologie) {
                $ors[] = $qb->expr()->eq('T.slug', ':typologie' . $key);
                $qb->setParameter('typologie' . $key, $typologie);
            }
            $qb->andWhere($qb->expr()->or(...$ors));
        }

        if (null !== $restauration) {
            $qb->andWhere('U.restauration = :restauration')
                ->setParameter('restauration', $restauration)
            ;
        }

        if (null !== $greenSpace) {
            $qb->andWhere('U.green_space = :greenSpace')
                ->setParameter('greenSpace', $greenSpace)
            ;
        }

        $percentages = $qb->executeQuery()->fetchAllAssociative();

        return $this->render('territoire.html.twig', [
            'territoire' => $territoire,
            'percentageGlobal' => $percentageGlobal,
            'percentages' => $percentages,
            'query' => [
                'thematiques' => $thematiques,
                'typologies' => $typologies,
                'restauration' => $restauration,
                'greenSpace' => $greenSpace,
            ],
        ]);
    }
}
