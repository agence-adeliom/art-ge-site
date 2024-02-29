<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\DashboardFilterDTO;
use Symfony\Contracts\EventDispatcher\Event;

class DashboardDataListsEvent extends Event
{
    /**
     * @var array<mixed>
     */
    private array $lists = [];

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

    public function getLists(): array
    {
        return $this->lists;
    }

    public function setLists(array $lists): void
    {
        $this->lists = $lists;
    }

    public function getReponsesIds(): array
    {
        return $this->reponsesIds;
    }
}
