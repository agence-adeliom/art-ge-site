<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\DashboardFilterDTO;
use App\Entity\Choice;
use App\Entity\Thematique;
use App\Repository\ChoiceRepository;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseIdsSelector;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class DashboardThematiqueController extends AbstractController
{
    /** @var string */
    final public const DASHBOARD_API_THEMATIQUE_GROUP = 'dashboard:api:thematique:read';

    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ChoiceRepository $choiceRepository,
        private readonly ReponseIdsSelector $reponseIdsSelector,
    ) {
    }

    #[OA\Tag(name: 'Dashboard')]
    #[OA\Get(summary: 'Retourne les scores des choix de la thématique en fonction des filtres choisis')]
    #[OA\PathParameter(
        name: 'identifier',
        description: 'le slug ou l\'identifiant unique du territoire',
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\PathParameter(
        name: 'slug',
        description: 'le slug de la thématique',
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
    #[Route('/api/dashboard/{identifier}/thematique/{slug}', name: 'app_dashboard_thematique', methods: ['GET'])]
    public function __invoke(
        string $identifier,
        Thematique $thematique,
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
                'links' => [],
                'data' => 'Territoire not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        $territoires = $this->territoireRepository->getAllBySlugs(array_values(array_merge($departments ?? [], $ots ?? [], $tourisms ?? [])));
        $dashboardFilterDTO = DashboardFilterDTO::from([
            'territoire' => $territoire,
            'territoires' => $territoires,
            'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
            'from' => $from,
            'to' => $to,
        ]);

        $reponsesIds = $this->reponseIdsSelector->getLastReponsesIds($dashboardFilterDTO);

        // le nombre de réponse pour chaque choix ainsi que les infos sur le choix
        $choices = $thematique->getQuestion()->getChoices()->map(function (Choice $choice) use ($reponsesIds) {
            $reponsesCountByChoice = $this->choiceRepository->getNumberOfReponses($choice, $reponsesIds);
            $numberOfReponses = count($reponsesIds);
            if ($numberOfReponses > 1) {
                $percentage = ($reponsesCountByChoice / $numberOfReponses) * 100;
            } else {
                $percentage = 100;
            }

            return [
                'slug' => $choice->getSlug(),
                'name' => $choice->getLibelle(),
                'percentage' => (int) round($percentage),
            ];
        });

        return $this->json([
            'status' => 'success',
            'links' => array_values($thematique->getLinks() ?? []),
            'data' => $choices,
        ], Response::HTTP_OK, [], ['groups' => self::DASHBOARD_API_THEMATIQUE_GROUP]);
    }
}
