<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\DashboardFilterDTO;
use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
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
        $responsesIds = $event->getReponsesIds();

        $repondants = $this->getRepondants($dashboardFilterDTO, $responsesIds);
        $event->setGlobals([
            /* @phpstan-ignore-next-line */
            'lastSubmission' => \DateTime::createFromFormat('Y-m-d H:i:s', $this->reponseRepository->getLastSubmissionDate($dashboardFilterDTO))->format('d.m.Y'),
            'repondants' => $repondants,
            'repondantsCount' => count($repondants),
            'score' => $this->reponseRepository->getPercentageGlobal($dashboardFilterDTO, $responsesIds),
            'piliers' => $this->scoreRepository->getPercentagesByPiliersGlobal($dashboardFilterDTO, $responsesIds),
        ]);
    }

    private function getRepondants(DashboardFilterDTO $dashboardFilterDTO, array $responsesIds): array
    {
        $repondantUrl = fn (array $repondant): array => [...$repondant, 'url' => $this->router->generate('app_resultat_single', ['uuid' => $repondant['uuid']], UrlGeneratorInterface::ABSOLUTE_URL)];
        $repondants = $this->reponseRepository->getRepondantsGlobal($dashboardFilterDTO, $responsesIds);
        $repondants = $this->addRepondants($dashboardFilterDTO, $repondants);
        $repondants = array_map($repondantUrl, $repondants);

        return $repondants;
    }

    // fonction qui ajoute a la liste des répondant des répondants qui ne seraient pas cherché normalement car hors département
    private function addRepondants(DashboardFilterDTO $dashboardFilterDTO, array $repondants): mixed
    {
        $departmentFound = false;
        foreach ($dashboardFilterDTO->getTerritoires() as $territoire) {
            if ($territoire->getArea() === TerritoireAreaEnum::DEPARTEMENT) {
                $departmentFound = true;
                if ($territoire->getCitiesToAdd()->count() > 0) {
                    $repondants = array_merge($repondants, $this->reponseRepository->getRepondantsToAdd($territoire->getInseesToAdd()));
                }
            }
        }
        if (!$departmentFound && $dashboardFilterDTO->getTerritoire()->getArea() === TerritoireAreaEnum::DEPARTEMENT && $dashboardFilterDTO->getTerritoire()->getCitiesToAdd()->count() > 0) {
            $repondants = array_merge($repondants, $this->reponseRepository->getRepondantsToAdd($dashboardFilterDTO->getTerritoire()->getInseesToAdd()));
        }

        return $repondants;
    }
}
