<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\TerritoireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class TerritoireRequestMatcher implements RequestMatcherInterface
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
    ) {}

    public function matches(Request $request): bool
    {
        if ('app_territoire_single' === $request->attributes->get('_route') && null !== $request->attributes->get('identifier')) {
            $territoire = $this->territoireRepository->getOneByUuidOrSlug($request->attributes->get('identifier'));
            if ($territoire && !$territoire->isPublic()) {
                return true;
            }
        }

        return false;
    }
}
