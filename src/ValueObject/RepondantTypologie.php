<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Entity\Repondant;

class RepondantTypologie
{
    private int $typologie;
    private bool $restauration;

    public static function fromRepondant(Repondant $repondant): RepondantTypologie
    {
        $repondantTypologie = new RepondantTypologie();
        $repondantTypologie->typologie = (int) $repondant->getTypologie()->getId();
        $repondantTypologie->restauration = $repondant->isRestauration();

        return $repondantTypologie;
    }

    public static function from(int $typologie, bool $restauration): RepondantTypologie
    {
        $repondantTypologie = new RepondantTypologie();
        $repondantTypologie->typologie = $typologie;
        $repondantTypologie->restauration = $restauration;

        return $repondantTypologie;
    }

    public function getTypologie(): int
    {
        return $this->typologie;
    }

    public function getRestauration(): bool
    {
        return $this->restauration;
    }
}
