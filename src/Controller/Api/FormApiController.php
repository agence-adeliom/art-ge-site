<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\QuestionRepository;
use App\Repository\ThematiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormApiController extends AbstractController
{
    /** @var string */
    final public const FORM_API_GROUP = 'form:read';

    public function __construct(
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly QuestionRepository $questionRepository,
    ) {}

    #[Route('/api/form', name: 'api_form_get', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $questions = $this->questionRepository->findAll();
        $thematiques = $this->thematiqueRepository->findAll();

        return $this->json([
            'thematiques' => $thematiques,
            'questions' => $questions,
        ], Response::HTTP_OK, [], ['groups' => self::FORM_API_GROUP]);
    }
}
