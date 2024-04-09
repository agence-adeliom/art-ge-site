<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Territoire;
use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TerritoireController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[Route('/territoire/{identifier}', name: 'app_territoire_single')]
    #[Template('territoire.html.twig')]
    public function __invoke(
        string $identifier,
    ): array {
        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (! $territoire) {
            throw new TerritoireNotFound();
        }

        if (! $territoire->isPublic()) {
            $user = $this->getUser();
            if ($user->getSlug() !== 'grand-est') {
                if (! $user || ! $user instanceof Territoire) {
                    throw new BadCredentialsException('Invalid credentials.');
                }
                if ($user->getSlug() !== $identifier) {
                    throw new BadCredentialsException('Invalid credentials.');
                }
            }
        }

        return [
        ];
    }
}
