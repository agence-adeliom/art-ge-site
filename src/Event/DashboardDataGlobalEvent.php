<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\DashboardFilterDTO;
use Symfony\Contracts\EventDispatcher\Event;

class DashboardDataGlobalEvent extends Event
{
    /**
     * @var array<mixed>
     */
    private array $globals = [];

    public function __construct(
        private readonly DashboardFilterDTO $dashboardFilterDTO,
    ) {
    }

    public function getDashboardFilterDTO(): DashboardFilterDTO
    {
        return $this->dashboardFilterDTO;
    }

    public function getGlobals(): array
    {
        return $this->globals;
    }

    public function setGlobals(array $globals): void
    {
        $this->globals = $globals;
    }
}
