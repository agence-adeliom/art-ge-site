<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Reponse;
use App\Entity\Score;
use App\Services\PercentagePresenter;
use App\Services\ResultatApiPresenter;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    public function __construct(
        private ResultatApiPresenter $resultatApiPresenter,
    ) {
    }

    /** @return array<mixed> */
    #[Route('/resultat/{uuid}', name: 'app_resultat_single', requirements: ['uuid' => '.*(?<!pdf)$'])]
    #[Template('home.html.twig')]
    public function __invoke(Reponse $reponse): array
    {
        return [
            'page' => 'resultat',
            'resultats' => $this->resultatApiPresenter->present($reponse),
        ];
    }
}
