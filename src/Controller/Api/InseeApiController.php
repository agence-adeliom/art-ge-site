<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\CityRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InseeApiController extends AbstractController
{
    /** @var string */
    final public const INSEE_API_GROUP = 'city:read';

    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {
    }

    #[OA\Tag(name: 'Insee')]
    #[OA\Get(summary: 'Retourne la liste de toutes les villes correspondant au code postal')]
    #[OA\PathParameter(
        name: 'zip',
        description: 'Code postal sur lequel faire la recherche de ville',
        schema: new OA\Schema(type: 'string'),
    )]
    #[Route('/api/insee/{zip}', name: 'api_insee_get', methods: ['GET'])]
    public function __invoke(string $zip): JsonResponse
    {
//        if ($zip === '51700') {
//            return $this->json(['name' => 'COEUR DE LA VALLEE', 'zip' => $zip, 'insee' => '51457'], Response::HTTP_OK, [], ['groups' => self::INSEE_API_GROUP]);
//        }
        $cities = $this->cityRepository->getByZipCode($zip);

        return $this->json($cities, Response::HTTP_OK, [], ['groups' => self::INSEE_API_GROUP]);
    }
}
