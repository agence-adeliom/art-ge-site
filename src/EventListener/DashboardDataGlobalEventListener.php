<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\DashboardDataGlobalEvent;
use App\Repository\ReponseRepository;
use App\Repository\ScoreRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: DashboardDataGlobalEvent::class)]
class DashboardDataGlobalEventListener
{
    public function __construct(
        private readonly ReponseRepository $reponseRepository,
        private readonly ScoreRepository $scoreRepository,
        private readonly RouterInterface $router,
    ) {
    }

    public function __invoke(DashboardDataGlobalEvent $event): void
    {
        $dashboardFilterDTO = $event->getDashboardFilterDTO();

        $repondantUrl = fn(array $repondant) : array => [...$repondant, 'url' => $this->router->generate('app_resultat_single', ['uuid' => $repondant['uuid']], UrlGeneratorInterface::ABSOLUTE_URL)];
        $repondants = array_map($repondantUrl, $this->reponseRepository->getRepondantsGlobal($dashboardFilterDTO));
        $event->setGlobals([
            'lastSubmission' => \DateTime::createFromFormat('Y-m-d H:i:s', $this->reponseRepository->getLastSubmissionDate($dashboardFilterDTO))->format('d.m.Y'),
            'repondants' => $repondants,
            'repondantsCount' => count($repondants), // 29 bas-rhin
            'score' => $this->reponseRepository->getPercentageGlobal($dashboardFilterDTO),
            'piliers' => $this->scoreRepository->getPercentagesByPiliersGlobal($dashboardFilterDTO),
        ]);
    }
}
