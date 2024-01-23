<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\TerritoireFilterDTO;
use Symfony\Contracts\EventDispatcher\Event;

class TerritoireDashboardScoresEvent extends Event
{
    /**
     * @var array<mixed>
     */
    private array $scores = [];

    public function __construct(
        private readonly TerritoireFilterDTO $territoireFilterDTO,
    ) {
    }

    public function getTerritoireFilterDTO(): TerritoireFilterDTO
    {
        return $this->territoireFilterDTO;
    }

    public function getScores(): array
    {
        return $this->scores;
    }

    public function setScores(array $scores): void
    {
        $this->scores = $scores;
    }
}
