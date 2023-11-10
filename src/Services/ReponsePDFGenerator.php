<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;
use Knp\Snappy\Pdf;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class ReponsePDFGenerator
{
    public function __construct(
        private readonly Pdf $pdf,
        private readonly Environment $twig,
        private readonly ParameterBagInterface $parameterBag,
    ) {}

    public function generatePdf(Reponse $reponse): void
    {
        /** @var string $pdfDirectory */
        $pdfDirectory = $this->parameterBag->get('pdf_directory');
        $template = $this->twig->render('pdf.html.twig', ['reponse' => $reponse]);
        $filename = sprintf('%s/%s.pdf', $pdfDirectory, $reponse->getUuid()->toBase32());
        $this->pdf->generateFromHtml($template, $filename, [], true);
    }
}
