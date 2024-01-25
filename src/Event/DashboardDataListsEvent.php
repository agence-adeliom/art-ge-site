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

    public function __construct(
        private readonly DashboardFilterDTO $dashboardFilterDTO,
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
}
