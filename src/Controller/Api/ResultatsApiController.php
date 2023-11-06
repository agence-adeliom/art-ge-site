<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\DepartmentRepository;
use App\Repository\ReponseRepository;
use App\Repository\TypologieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ResultatsApiController extends AbstractController
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ReponseRepository $reponseRepository,
    ) {}

    #[Route('/api/resultats', name: 'api_resultats_get', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $avgMeanPointsOfAllReponses = $this->reponseRepository->getAverageMeanPointsOfAllReponses();
        $highestPointsOfAllReponses = $this->reponseRepository->getHighestPointsOfAllReponses();
        $lowestPointsOfAllReponses = $this->reponseRepository->getLowestPointsOfAllReponses();

        $data = [
            'average_mean_points' => $avgMeanPointsOfAllReponses,
            'highest_points' => $highestPointsOfAllReponses,
            'lowest_points' => $lowestPointsOfAllReponses,
        ];

        $departments = $this->departmentRepository->findAll();
        foreach ($departments as $department) {
            $slug = $department->getSlug();
            $avgMeanPointsOfDepartment = $this->reponseRepository->getAverageMeanPointsOfDepartment($slug);
            $highestPointsOfDepartment = $this->reponseRepository->getHighestPointsOfDepartment($slug);
            $lowestPointsOfDepartment = $this->reponseRepository->getLowestPointsOfDepartment($slug);

            $data['departments'][$slug] = [
                'average_mean_points' => $avgMeanPointsOfDepartment,
                'highest_points' => $highestPointsOfDepartment,
                'lowest_points' => $lowestPointsOfDepartment,
            ];
        }

        $typologies = $this->typologieRepository->findAll();
        foreach ($typologies as $typologie) {
            $slug = $typologie->getSlug();
            $avgMeanPointsOfTypologie = $this->reponseRepository->getAverageMeanPointsOfTypologie($slug);
            $highestPointsOfTypologie = $this->reponseRepository->getHighestPointsOfTypologie($slug);
            $lowestPointsOfTypologie = $this->reponseRepository->getLowestPointsOfTypologie($slug);

            $data['typologies'][$slug] = [
                'average_mean_points' => $avgMeanPointsOfTypologie,
                'highest_points' => $highestPointsOfTypologie,
                'lowest_points' => $lowestPointsOfTypologie,
            ];
        }

        return $this->json($data);
    }
}
