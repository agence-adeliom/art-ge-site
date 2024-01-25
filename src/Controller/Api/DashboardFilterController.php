<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class DashboardFilterController extends AbstractController
{
    /** @var string[] */
    private array $columns = ['slug', 'name'];

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
    ) {
    }

    #[OA\Tag(name: 'Dashboard')]
    #[OA\Get(summary: 'Retourne les filtres en fonction du territoire')]
    #[OA\PathParameter(
        name: 'identifier',
        description: 'le slug ou l\'identifiant unique du territoire',
        schema: new OA\Schema(type: 'string'),
    )]
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
    #[Route('/api/dashboard/{identifier}/filters', name: 'app_dashboard_filters', methods: ['GET'])]
    public function __invoke(
        string $identifier,
        #[MapQueryParameter] ?array $departments = [],
        #[MapQueryParameter] ?array $ots = [],
        #[MapQueryParameter] ?array $tourisms = [],
    ): JsonResponse {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            return new JsonResponse([
                'status' => 'error',
                'data' => 'Territoire not found',
            ], 200);
        }

        if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
            return new JsonResponse([
                'status' => 'success',
                'data' => $this->getDataByDepartment($territoire),
            ], 200);
        }

        if (TerritoireAreaEnum::OT === $territoire->getArea()) {
            return new JsonResponse([
                'status' => 'success',
                'data' => $this->getDataByOT($territoire),
            ], 200);
        }

        return new JsonResponse([
            'status' => 'success',
            'data' => $this->getAllDatas($departments),
        ], 200);
    }

    /**
     * @param array<string> $departmentsFilters
     *
     * @return array<mixed>
     */
    private function getAllDatas(array $departmentsFilters = null): array
    {
        if (null === $departmentsFilters || [] === $departmentsFilters) {
            $ots = $this->allOts();
        } else {
            $ots = $this->territoireRepository->getOTsByDepartments($departmentsFilters, $this->columns);
        }

        return [
            'departments' => $this->allDepartments(),
            'ots' => $ots,
            'tourisms' => $this->allTourisms() ?: null,
            'typologies' => $this->typologieRepository->getSlugsAndNames(),
        ];
    }

    private function getDataByDepartment(Territoire $department): array
    {
        $departments = $this->allDepartments();
        $ots = $this->territoireRepository->getOTsByDepartments([$department->getSlug()], $this->columns);
        $tourisms = $this->territoireRepository->getTourismsByLinkedTerritoire($department, $this->columns);

        return [
            'departments' => $departments,
            'ots' => $ots,
            'tourisms' => $tourisms ?: null,
            'typologies' => $this->typologieRepository->getSlugsAndNames(),
        ];
    }

    private function getDataByOt(Territoire $ot): array
    {
        $tourisms = $this->territoireRepository->getTourismsByLinkedTerritoire($ot, $this->columns) ?: null;

        return [
            'departments' => null,
            'ots' => null,
            'tourisms' => $tourisms ?: null,
            'typologies' => $this->typologieRepository->getSlugsAndNames(),
        ];
    }

    private function allDepartments(): null | array | Territoire
    {
        return $this->territoireRepository->getAllByType(TerritoireAreaEnum::DEPARTEMENT, $this->columns);
    }

    private function allOts(): null | array | Territoire
    {
        return $this->territoireRepository->getAllByType(TerritoireAreaEnum::OT, $this->columns);
    }

    private function allTourisms(): null | array | Territoire
    {
        return $this->territoireRepository->getAllByType(TerritoireAreaEnum::TOURISME, $this->columns);
    }
}
