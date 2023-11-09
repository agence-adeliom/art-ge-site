<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\DepartmentRepository;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class ResultatsApiController extends AbstractController
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly ReponseRepository $reponseRepository,
        private readonly ScoreRepository $scoreRepository,
    ) {}

    #[Route('/api/resultats', name: 'api_resultats_get', methods: ['GET'])]
    public function __invoke(
        #[MapQueryParameter] ?bool $restauration,
        #[MapQueryParameter(name: 'green_space')] ?bool $greenSpace,
    ): JsonResponse {
        $avgMeanPointsOfAllReponses = $this->reponseRepository->getAverageMeanPointsOfAllReponses($restauration, $greenSpace);
        $highestPointsOfAllReponses = $this->reponseRepository->getHighestPointsOfAllReponses($restauration, $greenSpace);
        $lowestPointsOfAllReponses = $this->reponseRepository->getLowestPointsOfAllReponses($restauration, $greenSpace);

        $data = [
            'average' => $avgMeanPointsOfAllReponses,
            'highest' => $highestPointsOfAllReponses,
            'lowest' => $lowestPointsOfAllReponses,
        ];

        $departments = $this->departmentRepository->findAll();
        foreach ($departments as $department) {
            $slug = $department->getSlug();
            $avgMeanPointsOfDepartment = $this->reponseRepository->getAverageMeanPointsOfDepartment($slug, $restauration, $greenSpace);
            $highestPointsOfDepartment = $this->reponseRepository->getHighestPointsOfDepartment($slug, $restauration, $greenSpace);
            $lowestPointsOfDepartment = $this->reponseRepository->getLowestPointsOfDepartment($slug, $restauration, $greenSpace);

            $data['departments'][$slug] = [
                'average' => $avgMeanPointsOfDepartment,
                'highest' => $highestPointsOfDepartment,
                'lowest' => $lowestPointsOfDepartment,
            ];
        }

        $typologies = $this->typologieRepository->findAll();
        foreach ($typologies as $typologie) {
            $slug = $typologie->getSlug();
            $avgMeanPointsOfTypologie = $this->reponseRepository->getAverageMeanPointsOfTypologie($slug, $restauration, $greenSpace);
            $highestPointsOfTypologie = $this->reponseRepository->getHighestPointsOfTypologie($slug, $restauration, $greenSpace);
            $lowestPointsOfTypologie = $this->reponseRepository->getLowestPointsOfTypologie($slug, $restauration, $greenSpace);

            $data['typologies'][$slug] = [
                'average' => $avgMeanPointsOfTypologie,
                'highest' => $highestPointsOfTypologie,
                'lowest' => $lowestPointsOfTypologie,
            ];
        }

        $thematiques = $this->thematiqueRepository->findAll();
        foreach ($thematiques as $thematique) {
            $slug = $thematique->getSlug();
            if ('label' === $slug) {
                continue;
            }
            $avgMeanPointsOfThematique = $this->scoreRepository->getAverageMeanPointsOfThematique($slug, $restauration, $greenSpace);
            $highestPointsOfThematique = $this->scoreRepository->getHighestPointsOfThematique($slug, $restauration, $greenSpace);
            $lowestPointsOfThematique = $this->scoreRepository->getLowestPointsOfThematique($slug, $restauration, $greenSpace);

            $data['thematiques'][$slug] = [
                'average' => $avgMeanPointsOfThematique,
                'highest' => $highestPointsOfThematique,
                'lowest' => $lowestPointsOfThematique,
            ];
        }

        return $this->json($data);
    }
}
