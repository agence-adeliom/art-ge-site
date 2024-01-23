<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TerritoireFilterDTO;
use App\Enum\TerritoireAreaEnum;
use App\Event\TerritoireDashboardGlobalEvent;
use App\Event\TerritoireDashboardScoresEvent;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class TerritoireController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param array<string>|null $typologies
     *
     * @return array<mixed>
     */
    #[Route('/territoire/{identifier}', name: 'app_territoire_single')]
    #[Template('territoire.html.twig')]
    public function __invoke(
        string $identifier,
        #[MapQueryParameter] ?array $typologies,
        #[MapQueryParameter] ?string $from,
        #[MapQueryParameter] ?string $to,
    ): array {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            throw new TerritoireNotFound();
        }

        $territoireFilterDTO = TerritoireFilterDTO::from([
            'territoire' => $territoire,
            'typologies' => $typologies,
            'from' => $from,
            'to' => $to,
        ]);

        $event = new TerritoireDashboardGlobalEvent($territoireFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $globals = $event->getGlobals();

        $event = new TerritoireDashboardScoresEvent($territoireFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $scores = $event->getScores();

        $children = [];
        if (in_array($territoire->getArea(), [TerritoireAreaEnum::DEPARTEMENT, TerritoireAreaEnum::REGION])) {
            $children = $territoire->getTerritoiresChildren();
        }

        return [
            'territoire' => $territoire,
            'children' => $children,
            'globals' => $globals,
            'scores' => $scores,
            'query' => [
                'typologies' => $territoireFilterDTO->getTypologies(),
                'from' => $territoireFilterDTO->getFrom(),
                'to' => $territoireFilterDTO->getTo(),
            ],
        ];
    }
}
