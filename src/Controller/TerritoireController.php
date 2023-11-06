<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\TerritoireNotFound;
use App\Repository\TerritoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TerritoireController extends AbstractController
{
    public function __construct(
        private readonly TerritoireRepository $territoireRepository,
    ) {}

    #[Route('/territoire/{identifier}', name: 'app_territoire_single')]
    public function __invoke(string $identifier, Request $request): Response
    {
        $territoire = null;

        if ($identifier) {
            $territoire = $this->territoireRepository->getOneByUuid($identifier);
            if (!$territoire) {
                $territoire = $this->territoireRepository->getOneBySlug($identifier);
            }
        }

        if (!$territoire) {
            throw new TerritoireNotFound();
        }

        return $this->render('territoire.html.twig', [
            'territoire' => $territoire,
        ]);
    }
}
