<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TerritoireFilterDTO;
use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
use App\Event\TerritoireDashboardGlobalEvent;
use App\Event\TerritoireDashboardScoresEvent;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use App\Repository\TypologieRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class TerritoireController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
        private readonly TypologieRepository $typologieRepository,
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
            'typologies' => $typologies ?? $this->typologieRepository->getSlugs(),
            'from' => $from,
            'to' => $to,
        ]);

        $event = new TerritoireDashboardGlobalEvent($territoireFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $globals = $event->getGlobals();

        $event = new TerritoireDashboardScoresEvent($territoireFilterDTO);
        $this->eventDispatcher->dispatch($event);
        $scores = $event->getScores();

        $subChildren = [];
        $children = $territoire->getTerritoiresChildren()->toArray();
        if ($territoire->getArea() === TerritoireAreaEnum::REGION) {
            foreach (array_map(fn (Territoire $child) => $child->getTerritoiresChildren()->toArray(), $children) as $subChild) {
                $subChildren = array_merge($subChildren, $subChild);
            }
        }

        return [
            'territoire' => $territoire,
            'children' => $children,
            'subChildren' => $subChildren,
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
