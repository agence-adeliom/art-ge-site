<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EncodedImage;
use App\Entity\Reponse;
use App\Repository\EncodedImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    #[Route('/resultat/{uuid}', name: 'app_resultat_single')]
    public function __invoke(Reponse $reponse): Response
    {
        return $this->render('resultat.html.twig', [
            'reponse' => $reponse,
        ]);
    }
}
