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

class InfoController extends AbstractController
{
    

    /** @return array<mixed> */
    #[Route('/informations', name: 'info')]
    #[Template('home.html.twig')]
    public function index(Request $request): array | RedirectResponse
    {
       return [];
    }
}
