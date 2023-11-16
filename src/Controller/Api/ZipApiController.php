<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\CityRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ZipApiController extends AbstractController
{
    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {}

    #[OA\Tag(name: 'Insee')]
    #[OA\Get(summary: 'Retourne tous les codes postaux pour l\'autocompletion')]
    #[OA\PathParameter(
        name: 'zip',
        description: 'Code postal sur lequel faire l\'autocompletion',
        schema: new OA\Schema(type: 'string'),
    )]
    #[Route('/api/zip/{zip}', name: 'api_zip_get', methods: ['GET'])]
    public function __invoke(string $zip): JsonResponse
    {
        $zips = $this->cityRepository->getZipCompletion($zip);

        return $this->json($zips);
    }
}
