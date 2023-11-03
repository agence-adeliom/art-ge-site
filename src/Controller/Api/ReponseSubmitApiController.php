<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\Form\ReponseType;
use App\Services\HandleFormSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ReponseSubmitApiController extends AbstractController
{
    public function __construct(
        private readonly HandleFormSubmission $handleFormSubmission,
    ) {
    }

    #[Route('/api/submit', name: 'api_submit_get', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            $reponse = ($this->handleFormSubmission)($reponseForm->getData());
            return $this->json(['uuid' => $reponse->getUuid(), 'link' => '/resultats/' . $reponse->getUuid()], Response::HTTP_OK);
        }

        return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
