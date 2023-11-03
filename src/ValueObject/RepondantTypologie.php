<?php

declare(strict_types=1);

namespace App\ValueObject;

class ScoreGeneration
{
    private int $points;
    private int $total;

    /**
     * @var array<\App\Entity\Score>
     */
    private array $scores;

    /**
     * @param array<\App\Entity\Score> $scores
     */
    public static function from(int $points, int $total, array $scores): ScoreGeneration
    {
        $scoreGeneration = new ScoreGeneration();
        $scoreGeneration->points = $points;
        $scoreGeneration->total = $total;
        $scoreGeneration->scores = $scores;

        return $scoreGeneration;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    /** @return array<\App\Entity\Score> */
    public function getScores(): array
    {
        return $this->scores;
    }
}
