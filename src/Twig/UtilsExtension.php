<?php

declare(strict_types=1);

namespace App\Twig;

use Adeliom\EasyBlockBundle\Block\AbstractBlock;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Entity\Block;
use App\Entity\Media;
use App\Entity\Reponse;
use App\Entity\StoreLocator\Shop;
use App\Repository\BlockRepository;
use App\Repository\Configuration\ReinsuranceRepository;
use App\Repository\Review\ReviewRepository;
use App\Services\DateService;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
        if ($reponse->getTotal() === 0) {
            // just in case division by 0
            return 0;
        }

        return (int) round($reponse->getPoints() / $reponse->getTotal() * 100);
    }
}
