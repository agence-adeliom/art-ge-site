<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reponse;
use App\Services\ResultatApiPresenter;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    public function __construct(
        private readonly ResultatApiPresenter $resultatApiPresenter,
    ) {
    }

    /** @return array<mixed> */
    #[Route('/resultat/{uuid}', name: 'app_resultat_single')]
    #[Template('resultat.html.twig')]
    public function __invoke(Reponse $reponse): array
    {
        return [
            'id' => $reponse->getId(),
            'page' => 'resultat',
            'resultats' => $this->resultatApiPresenter->present($reponse),
            'repondant' => $reponse->getRepondant()->getCompany()
        ];
    }
}
