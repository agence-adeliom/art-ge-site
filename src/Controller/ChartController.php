<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
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
                foreach ($images as $key => $image){
                    file_put_contents('images' . $key . '.jpeg', file_get_contents($image));
                }
            } catch (\Throwable $e) {
                return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        return new Response(null, Response::HTTP_CREATED);
    }
}
