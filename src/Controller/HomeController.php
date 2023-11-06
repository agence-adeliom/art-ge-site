<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Form\ReponseType;
use App\Services\HandleFormSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly HandleFormSubmission $handleFormSubmission,
    ) {}

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            ($this->handleFormSubmission)($reponseForm->getData());

            return $this->render('success.html.twig', [
                'form' => $reponseForm,
            ]);
        }

        return $this->render('home.html.twig', [
            'form' => $reponseForm,
        ]);
    }
}
