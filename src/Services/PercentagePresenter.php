<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;

class PercentagePresenter
{
    public function displayPercentage(float | int | Reponse $amount, float | int $total = 100): int
    {
        if ($amount instanceof Reponse) {
            $percentage = $this->getReponsePercentage($amount);
        } else {
            $percentage = $this->getPercentage($amount, $total);
        }

        return $percentage;
    }

    public function displayPercentageWithSign(float | int | Reponse $amount, float | int $total = 100): string
    {
        return $this->displayPercentage($amount, $total) . '%';
    }

    private function getPercentage(float | int $amount, float | int $total): int
    {
        if (0 === $total) {
            // just in case division by 0
            return 0;
        }

        return (int) round($amount / $total * 100);
    }

    private function getReponsePercentage(Reponse $reponse): int
    {
        return $this->getPercentage($reponse->getPoints(), $reponse->getTotal());
    }
}
