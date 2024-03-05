<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\Form\ReponseType;
use App\Services\ReponseFormSubmission;
use App\Services\ResultatApiPresenter;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ReponseSubmitApiController extends AbstractController
{
    public function __construct(
        private readonly ReponseFormSubmission $reponseFormSubmission,
        private readonly RouterInterface $router,
        private readonly ResultatApiPresenter $resultatApiPresenter,
    ) {
    }

    #[OA\Tag(name: 'Formulaire')]
    #[OA\Post(summary: 'Soumet un formulaire pour validation')]
    #[Route('/api/submit', name: 'api_submit_get', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            $reponse = $this->reponseFormSubmission->updateAndSaveReponse($reponseForm->getData());

            return $this->json([
                'uuid' => $reponse->getUuid(),
                'link' => $this->router->generate('app_resultat_single', ['uuid' => $reponse->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL),
                'resultats' => $this->resultatApiPresenter->present($reponse),
            ], Response::HTTP_OK);
        }

        return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
