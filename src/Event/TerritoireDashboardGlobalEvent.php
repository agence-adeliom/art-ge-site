<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\TerritoireFilterDTO;
use Symfony\Contracts\EventDispatcher\Event;

class TerritoireDashboardGlobalEvent extends Event
{
    /**
     * @var array<mixed>
     */
    private array $globals = [];

    public function __construct(
        private readonly TerritoireFilterDTO $territoireFilterDTO,
    ) {
    }

    public function getTerritoireFilterDTO(): TerritoireFilterDTO
    {
        return $this->territoireFilterDTO;
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
