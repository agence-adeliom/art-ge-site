<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\DashboardFilterDTO;
use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
use App\Event\DashboardDataGlobalEvent;
use App\Event\DashboardDataListsEvent;
use App\Event\DashboardDataScoresEvent;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseIdsSelector;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class DashboardDataController extends AbstractController
{
    /** @var string */
    final public const DASHBOARD_API_DATA_GROUP = 'dashboard:api:data:read';

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ReponseIdsSelector $reponseIdsSelector,
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
        Request $request,
        string $identifier,
        #[MapQueryParameter] ?array $departments = [],
        #[MapQueryParameter] ?array $ots = [],
        #[MapQueryParameter] ?array $tourisms = [],
        #[MapQueryParameter] ?array $typologies = [],
        #[MapQueryParameter] string $from = '2024-01-01',
        #[MapQueryParameter] string $to = null,
    ): JsonResponse {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            return $this->json([
                'status' => 'error',
                'data' => 'Territoire not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        $isPublic = $territoire->getSlug() === 'grand-est' || $territoire->isPublic();
        $username = $request->headers->get('X-User-Name');
        $usertoken = $request->headers->get('X-User-Token');
        if (!$territoire->isPublic() && $territoire->getSlug() !== 'grand-est') {
            if ($username === $territoire->getSlug() && $usertoken === $territoire->getCode()) {
                $isPublic = false;
            } else {
                return $this->json([
                    'status' => 'error',
                    'data' => 'Invalid credentials.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        if ($territoire->getSlug() === 'grand-est') {
            $isPublic = $username !== $territoire->getSlug() || $usertoken !== $territoire->getCode();
        }

        $territoires = self::excludeParentDepartmentIfOT(
            $territoire,
            $this->territoireRepository->getAllBySlugs(array_values(array_merge($departments ?? [], $ots ?? [], $tourisms ?? [])))
        );

        if (!$isPublic) {
            $dashboardFilterDTO = DashboardFilterDTO::from([
                'territoire' => $territoire,
                'territoires' => $territoires,
                'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
                'from' => $from,
                'to' => $to,
            ]);
        } else {
            $dashboardFilterDTO = DashboardFilterDTO::from([
                'territoire' => $territoire,
                'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
                'from' => $from,
                'to' => $to,
            ]);
        }

        $reponsesIds = $this->reponseIdsSelector->getLastReponsesIds($dashboardFilterDTO);

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

        $data = [
            'globals' => $globals,
            'scores' => $scores,
            'lists' => $lists,
        ];

        if ($isPublic) {
            $data = $this->restrictPublicData($data);
        }

        return $this->json([
            'status' => 'success',
            'data' => $data,
        ], Response::HTTP_OK, [], ['groups' => self::DASHBOARD_API_DATA_GROUP]);
    }

    /**
     * Enleve de la liste des territoires le département parent s'il fait partie des filtres
     * et que l'OT enfant fait aussi parti des filtres.
     * Par exemple si je filtre sur Alsace et sur Grand-Ried, alors le problème c'est que
     * grand-ried est contenu dans Alsace, alors il n'y a pas de changement.
     * Tandis que si on choisi Alsace et ensuite Grand-Ried cela montre une volonté de
     * mieux filtrer.
     *
     * @param array<Territoire> $territoires
     *
     * @return array<Territoire>
     */
    public static function excludeParentDepartmentIfOT(Territoire $mainTerritoire, array $territoires = []): array
    {
        $territoiresKeys = [];
        foreach ($territoires as $territoire) {
            if (TerritoireAreaEnum::OT === $territoire->getArea()) {
                foreach ($territoires as $territoire2) {
                    if ($territoire->getParents()->contains($territoire2)) {
                        $territoiresKeys[] = $territoire2->getId();
                    }
                }
            }
        }

        if (TerritoireAreaEnum::DEPARTEMENT === $mainTerritoire->getArea()) {
            foreach ($territoires as $t) {
                if (in_array($t, $mainTerritoire->getTerritoiresChildren()->toArray())) {
                    $territoiresKeys[] = $mainTerritoire->getId();
                }
            }
        }

        return array_values(array_filter($territoires, fn (Territoire $territoire) => ! in_array($territoire->getId(), $territoiresKeys)));
    }

    private function restrictPublicData (array $data)
    {
        foreach ($data['globals']['repondants'] as $key => $value) {
            unset($data['globals']['repondants'][$key]['uuid']);
            unset($data['globals']['repondants'][$key]['url']);
            unset($data['globals']['repondants'][$key]['company']);
        }
        unset($data['lists']);
        return $data;
    }
}
