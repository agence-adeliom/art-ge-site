<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\DashboardFilterDTO;
use Symfony\Contracts\EventDispatcher\Event;

class DashboardDataScoresEvent extends Event
{
    /**
     * @var array<mixed>
     */
    private array $scores = [];

    /**
     * @param array<int> $reponsesIds
     */
    public function __construct(
        private readonly DashboardFilterDTO $dashboardFilterDTO,
        private readonly array $reponsesIds = []
    ) {
    }

    public function getDashboardFilterDTO(): DashboardFilterDTO
    {
        return $this->dashboardFilterDTO;
    }

    public function getScores(): array
    {
        return $this->scores;
    }

    public function setScores(array $scores): void
    {
        $this->scores = $scores;
    }

    public function getReponsesIds(): array
    {
        return $this->reponsesIds;
    }
}
