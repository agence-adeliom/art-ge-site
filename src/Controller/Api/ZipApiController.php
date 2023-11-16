<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ZipApiController extends AbstractController
{
    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {}

    #[Route('/api/zip/{zip}', name: 'api_zip_get', methods: ['GET'])]
    public function __invoke(string $zip): JsonResponse
    {
        $zips = $this->cityRepository->getZipCompletion($zip);

        return $this->json($zips);
    }
}
