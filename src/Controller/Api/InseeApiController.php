<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InseeApiController extends AbstractController
{
    /** @var string */
    final public const INSEE_API_GROUP = 'city:read';

    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {}

    #[Route('/api/insee/{zip}', name: 'api_insee_get', methods: ['GET'])]
    public function __invoke(string $zip): JsonResponse
    {
        $cities = $this->cityRepository->getByZipCode($zip);

        return $this->json($cities, Response::HTTP_OK, [], ['groups' => self::INSEE_API_GROUP]);
    }
}
