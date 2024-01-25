<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\DashboardFilterDTO;
use App\Event\DashboardDataGlobalEvent;
use App\Event\DashboardDataListsEvent;
use App\Event\DashboardDataScoresEvent;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
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
        #[MapQueryParameter] ?string $from = null,
        #[MapQueryParameter] ?string $to = null,
    ): JsonResponse {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            return $this->json([
                'status' => 'error',
                'data' => 'Territoire not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        $territoires = $this->territoireRepository->getAllBySlugs(array_values(array_merge($departments,$ots,$tourisms,)));
        $dashboardFilterDTO = DashboardFilterDTO::from([
            'territoire' => $territoire,
            'territoires' => $territoires,
            'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
            'from' => $from,
            'to' => $to,
        ]);

        $event = new DashboardDataGlobalEvent($dashboardFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $globals = $event->getGlobals();

        $event = new DashboardDataScoresEvent($dashboardFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $scores = $event->getScores();

        $event = new DashboardDataListsEvent($dashboardFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $lists = $event->getLists();

        return $this->json([
            'status' => 'success',
            'data' => [
                'globals' => $globals,
                'scores' => $scores,
                'lists' => $lists,
            ],
        ], Response::HTTP_OK, [], ['groups' => self::DASHBOARD_API_DATA_GROUP]);
    }
}
