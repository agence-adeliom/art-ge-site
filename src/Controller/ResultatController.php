<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reponse;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    // /** @return array<mixed> */
    // #[Route('/resultat/{uuid}', name: 'app_resultat_single', requirements: ['uuid' => '.*(?<!pdf)$'])] // ne doit pas finir par "pdf"
    // #[Template('resultat.html.twig')]
    // public function __invoke(Reponse $reponse): array
    // {
    //     return [
    //         'reponse' => $reponse,
    //     ];
    // }
     /** @return array<mixed> */
     #[Route('/resultat/{uuid}', name: 'app_resultat_single', requirements: ['uuid' => '.*(?<!pdf)$'])]
     #[Template('home.html.twig')]
     public function __invoke(Reponse $reponse): array
     {
         return [];
     }
}
