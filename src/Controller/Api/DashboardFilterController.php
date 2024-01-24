<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Enum\TerritoireAreaEnum;
use App\Repository\TerritoireRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class DashboardFilterController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
    ) {}

    #[OA\Tag(name: 'Dashboard')]
    #[OA\Get(summary: 'Retourne les filtres en fonction du territoire')]
    #[OA\QueryParameter(
        name: 'departments',
        description: 'un ou plusieurs slug de departements',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')),
    )]
    #[OA\QueryParameter(
        name: 'ots',
        description: 'un ou plusieurs slug d\'offices de tourisme',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')),
    )]
    #[OA\QueryParameter(
        name: 'tourisms',
        description: 'un ou plusieurs slug de tourismes',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')),
    )]
    #[Route('/dashboard/filters', name: 'app_dashboard_filters')]
    public function __invoke(
        #[MapQueryParameter] ?array $departments = [],
        #[MapQueryParameter] ?array $ots = [],
        #[MapQueryParameter] ?array $tourisms = [],
    ): JsonResponse
    {

        $columns = ['slug', 'name'];
        $departments = $this->territoireRepository->getAllByType(TerritoireAreaEnum::DEPARTEMENT, $columns);
        $ots = $this->territoireRepository->getAllByType(TerritoireAreaEnum::OT, $columns);
        $tourisms = $this->territoireRepository->getAllByType(TerritoireAreaEnum::TOURISME, $columns);

        $datas = [
            'departments' => $departments,
            'ots' => $ots,
            'tourisms' => $tourisms,
        ];

            return new JsonResponse([
                'status' => 'success',
                'data' => $datas,
            ], 200);

//            $columns = ['slug', 'name'];
//            if ($territoire->getArea() === TerritoireAreaEnum::REGION) {
//            } elseif ($territoire->getArea() === TerritoireAreaEnum::DEPARTEMENT) {
//                // only OTs of the department
//                $ots = $this->territoireRepository->getOTsByDepartment($territoire, $columns);
//                $tourisms = $this->territoireRepository->getTourismsByLinkedTerritoire($territoire, $columns);
//            }
//
//            if ($territoire->getArea() === TerritoireAreaEnum::REGION) {
//            }
//
//            return new JsonResponse([
//                'status' => 'success',
//                'datas' => $datas
//            ], 200);
    }

}
