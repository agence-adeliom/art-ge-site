<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Reponse;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UtilsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('reponse_percentage', $this->getReponsePercentage(...)),
        ];
    }

    public function getReponsePercentage(Reponse $reponse): int
    {
        if (0 === $reponse->getTotal()) {
            // just in case division by 0
            return 0;
        }

        return (int) round($reponse->getPoints() / $reponse->getTotal() * 100);
    }
}
