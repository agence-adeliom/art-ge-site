<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    /** @return array<mixed> */
    #[Route('/informations', name: 'info')]
    #[Template('home.html.twig')]
    public function index() : array | RedirectResponse
    {
        return [];
    }
}
