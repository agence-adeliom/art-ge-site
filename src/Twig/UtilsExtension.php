<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Reponse;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UtilsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('display_percentage', $this->displayPercentage(...)),
        ];
    }

    public function displayPercentage(float | int | Reponse $amount, float | int $total = 100): string
    {
        if ($amount instanceof Reponse) {
            $percentage = $this->getReponsePercentage($amount);
        } else {
            $percentage = $this->getPercentage($amount, $total);
        }

        return $percentage . '%';
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
