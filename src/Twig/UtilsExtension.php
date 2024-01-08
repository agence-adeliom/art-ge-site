<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Reponse;
use App\Services\PercentagePresenter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UtilsExtension extends AbstractExtension
{
    public function __construct(
        private PercentagePresenter $percentagePresenter,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('display_percentage', $this->percentagePresenter->displayPercentageWithSign(...)),
        ];
    }
}
