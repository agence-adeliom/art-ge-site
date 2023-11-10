<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ResultatPdfController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {}

    #[Route('/resultat/{uuid}{extension}', name: 'app_resultat_pdf', requirements: ['extension' => '.pdf$'])]
    public function __invoke(Reponse $reponse): Response
    {
        /** @var string $pdfDirectory */
        $pdfDirectory = $this->parameterBag->get('pdf_directory');
        $uuid = $reponse->getUuid()->toBase32();
        $filename = sprintf('%s/%s.pdf', $pdfDirectory, $uuid);

        return $this->file($filename, $uuid, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
