<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\QuestionRepository;
use App\Repository\ThematiqueRepository;
use App\Services\ChoiceIgnorer\GreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationChoiceIgnorer;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class FormApiController extends AbstractController
{
    /** @var string */
    final public const FORM_API_GROUP = 'form:read';

    public function __construct(
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly QuestionRepository $questionRepository,
        private readonly GreenSpaceChoiceIgnorer $greenSpaceChoiceIgnorer,
        private readonly RestaurationChoiceIgnorer $restaurationChoiceIgnorer,
    ) {
    }

    #[OA\Tag(name: 'Formulaire')]
    #[OA\Get(summary: 'Retourne la liste des questions et des réponses associés ainsi que la liste des thématiques')]
    #[OA\Parameter(
        name: 'restauration',
        description: 'Est-ce que le répondant propose une offre de restauration ou non ?',
        in: 'query',
        schema: new OA\Schema(type: 'boolean'),
    )]
    #[OA\Parameter(
        name: 'green_space',
        description: 'Est-ce que le répondant à une offre d\'espace vert ou non ?',
        in: 'query',
        schema: new OA\Schema(type: 'boolean'),
    )]
    #[Route('/api/form', name: 'api_form_get', methods: ['GET'])]
    public function __invoke(
        #[MapQueryParameter(name: 'restauration')] ?bool $restauration = false,
        #[MapQueryParameter(name: 'green_space')] ?bool $greenSpace = false
    ): JsonResponse {
        $questions = $this->questionRepository->findAll();
        $thematiques = $this->thematiqueRepository->findAll();

        if (false === $greenSpace) {
            $questions = array_map($this->greenSpaceChoiceIgnorer->ignoreChoices(...), $questions);
        }

        if (false === $restauration) {
            /** @phpstan-ignore-next-line */
            $questions = array_map($this->restaurationChoiceIgnorer->ignoreChoices(...), $questions);
        }

        return $this->json([
            'thematiques' => $thematiques,
            'questions' => $questions,
        ], Response::HTTP_OK, [], ['groups' => self::FORM_API_GROUP]);
    }
}
