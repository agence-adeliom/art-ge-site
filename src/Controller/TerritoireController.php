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
        $user = $this->getUser();
        if ($user instanceof Territoire && $user->getSlug() !== 'grand-est') {
            if ($user->getSlug() !== $identifier) {
                throw new BadCredentialsException('Invalid credentials.');
            }
        }

        $territoire = $this->territoireRepository->getOneByUuidOrSlug($identifier);

        if (!$territoire) {
            throw new TerritoireNotFound();
        }

        return [
        ];
    }
}
