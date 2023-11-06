<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Form\ReponseType;
use App\Services\ReponseFormSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly ReponseFormSubmission $reponseFormSubmission,
        private readonly RouterInterface $router,
    ) {}

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            $reponse = $this->reponseFormSubmission->updateAndSaveReponse($reponseForm->getData());

            return $this->redirect($this->router->generate('app_resultat_single', ['uuid' => $reponse->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return $this->render('home.html.twig', [
            'form' => $reponseForm,
        ]);
    }
}
