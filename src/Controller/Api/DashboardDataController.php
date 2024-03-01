<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\DashboardFilterDTO;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use App\Event\DashboardDataGlobalEvent;
use App\Event\DashboardDataListsEvent;
use App\Event\DashboardDataScoresEvent;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class DashboardDataController extends AbstractController
{
    /** @var string */
    final public const DASHBOARD_API_DATA_GROUP = 'dashboard:api:data:read';

    /** @var string[] */
    private array $columns = ['slug', 'name'];

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[OA\Tag(name: 'Dashboard')]
    #[OA\Get(summary: 'Retourne les données en fonction des filtres choisis')]
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
    #[OA\QueryParameter(
        name: 'typologies',
        description: 'un ou plusieurs slug de typologie',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')),
    )]
    #[OA\QueryParameter(
        name: 'from',
        description: 'le début de la période recherchée',
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\QueryParameter(
        name: 'to',
        description: 'la fin de la période recherchée',
        schema: new OA\Schema(type: 'string'),
    )]
    #[Route('/api/dashboard/{identifier}/data', name: 'app_dashboard_data', methods: ['GET'])]
    public function __invoke(
        string $identifier,
        #[MapQueryParameter] ?array $departments = [],
        #[MapQueryParameter] ?array $ots = [],
        #[MapQueryParameter] ?array $tourisms = [],
        #[MapQueryParameter] ?array $typologies = [],
        #[MapQueryParameter] string $from = null,
        #[MapQueryParameter] string $to = null,
    ): JsonResponse {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            return $this->json([
                'status' => 'error',
                'data' => 'Territoire not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        $territoires = $this->territoireRepository->getAllBySlugs(array_values(array_merge($departments, $ots, $tourisms)));
        $dashboardFilterDTO = DashboardFilterDTO::from([
            'territoire' => $territoire,
            'territoires' => $territoires,
            'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
            'from' => $from,
            'to' => $to,
        ]);

        $reponsesIds = $this->getLastReponsesIds($dashboardFilterDTO);

        try {
            $event = new DashboardDataGlobalEvent($dashboardFilterDTO, $reponsesIds);
            $this->eventDispatcher->dispatch($event);
            $globals = $event->getGlobals();

            $event = new DashboardDataScoresEvent($dashboardFilterDTO, $reponsesIds);
            $this->eventDispatcher->dispatch($event);
            $scores = $event->getScores();

            $event = new DashboardDataListsEvent($dashboardFilterDTO, $reponsesIds);
            $this->eventDispatcher->dispatch($event);
            $lists = $event->getLists();
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR, [], ['groups' => self::DASHBOARD_API_DATA_GROUP]);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'globals' => $globals,
                'scores' => $scores,
                'lists' => $lists,
            ],
        ], Response::HTTP_OK, [], ['groups' => self::DASHBOARD_API_DATA_GROUP]);
    }

    private function getLastReponsesIds(DashboardFilterDTO $dashboardFilterDTO): array
    {
        $zipCriteria = '';
        $zipParams = [];
        $territoire = $dashboardFilterDTO->getTerritoire();
        if (TerritoireAreaEnum::REGION !== $territoire->getArea()) {
            if (TerritoireAreaEnum::DEPARTEMENT === $territoire->getArea()) {
                $department = DepartementEnum::tryFrom($territoire->getSlug());
                if ($department) {
                    if ($department === DepartementEnum::ALSACE) {
                        $zipCriteria = 'AND U.zip BETWEEN :zip67 AND :zip69';
                        $zipParams = ['zip67' => '67%', 'zip69' => '69%'];
                    } else {
                        $zipCriteria = 'AND U.zip LIKE :zip';
                        $zipParams = ['zip' => DepartementEnum::getCode($department) . '%'];
                    }
                }
            } else {
                $zipCriteria = 'AND U.zip IN (:zip)';
                $zipParams = ['zip' => $territoire->getZips()];
            }
        }

        $typologyCriteria = '';
        $typologyParams = [];
        if ([] !== $dashboardFilterDTO->getTypologies() ?? []) {
            $typologyCriteriaTemp = [];
            $typologyCriteria = 'AND (';
            foreach ($dashboardFilterDTO->getTypologies() ?? [] as $key => $typology) {
                $typologyCriteriaTemp[] = 'TY.slug = :typology' . $key;
                $typologyParams['typology' . $key] = $typology;
            }
            $typologyCriteria .= implode(' OR ', $typologyCriteriaTemp) . ') ';
        }

        $dateCriteria = '';
        $dateParams = [];
        if ($dashboardFilterDTO->hasDateRange()) {
            $dateFormat = 'Y-m-d H:i:s';
            $dateCriteria = 'AND ';
            if (null !== $dashboardFilterDTO->getFrom() && null !== $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at BETWEEN :from AND :to';
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->format($dateFormat);
                $dateParams['to'] = $dashboardFilterDTO->getTo()->format($dateFormat);
            } elseif (null !== $dashboardFilterDTO->getFrom() && null === $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at >= :from';
                $dateParams['from'] = $dashboardFilterDTO->getFrom()->format($dateFormat);
            } elseif (null === $dashboardFilterDTO->getFrom() && null !== $dashboardFilterDTO->getTo()) {
                $dateCriteria .= 'R.submitted_at <= :to';
                $dateParams['to'] = $dashboardFilterDTO->getTo()->format($dateFormat);
            }
        }

        return $this->entityManager->getConnection()->executeQuery('
            SELECT R.id, MAX(R.submitted_at)
            FROM reponse R
            INNER JOIN repondant U ON U.id = R.repondant_id 
            INNER JOIN typologie TY ON TY.id = U.typologie_id 
            WHERE 1 = 1
                    ' . $typologyCriteria . '
                    ' . $zipCriteria . '
                    ' . $dateCriteria . '
            GROUP BY R.repondant_id'
            , [...$typologyParams, ...$zipParams, ...$dateParams])->fetchFirstColumn();
    }
}
