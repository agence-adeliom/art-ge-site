<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EncodedImage;
use App\Repository\EncodedImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    public function __construct(
        private readonly Pdf $pdf,
        private readonly EntityManagerInterface $entityManager,
        private readonly EncodedImageRepository $encodedImageRepository,
    ) {
    }

    #[Route('/chart', name: 'app_chart')]
    public function index(Request $request): Response
    {
        return $this->render('chart/index.html.twig', [
            'controller_name' => 'ChartController',
        ]);
    }

    #[Route('/save-images', name: 'app_save_images', methods: ['POST'])]
    public function saveImages(Request $request): Response
    {
        $images = $request->request->all('images');
        if (!empty($images)) {
            try {
                foreach ($images as $data) {
                    $image = new EncodedImage();
                    $image->setData($data);
                    $this->entityManager->persist($image);
                }
                $this->entityManager->flush();
            } catch (\Throwable $e) {
                return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/create-pdf', name: 'app_save_pdf')]
    public function savePdf(Request $request): BinaryFileResponse
    {
        $template = $this->renderView('test.html.twig', ['images' => $this->encodedImageRepository->findAll()]);
        $filename = 'test.pdf';
        $this->pdf->generateFromHtml($template, $filename, [], true);

        return $this->file($filename, $filename, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
