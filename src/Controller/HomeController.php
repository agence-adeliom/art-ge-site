<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Services\ReponseFormSubmission;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly ReponseFormSubmission $reponseFormSubmission,
        private readonly RouterInterface $router,
        private readonly QuestionRepository $questionRepository,
    ) {}

    /** @return array<mixed> */
    #[Route('/', name: 'home')]
    #[Template('home.html.twig')]
    public function index(Request $request): array | RedirectResponse
    {
        $questions = $this->questionRepository->findAll();
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            $reponse = $this->reponseFormSubmission->updateAndSaveReponse($reponseForm->getData());

            return $this->redirect($this->router->generate('app_resultat_single', ['uuid' => $reponse->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return [
            'form' => $reponseForm,
            'questions' => $questions,
        ];
    }
}
