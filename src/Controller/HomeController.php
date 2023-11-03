<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Form\ReponseType;
use App\Repository\ChoiceTypologieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $reponseForm = $this->createForm(ReponseType::class);

        $reponseForm->handleRequest($request);
        if ($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            /** @var \App\Entity\Reponse $response */
            $response = $reponseForm->getData();
            $response->setCompleted(true);
            $response->setCreatedAt(new \DateTimeImmutable());
            $response->setSubmittedAt(new \DateTimeImmutable());
            $response->setUuid(new Ulid());
            $points = $response->getProcessedForm()['points'];
            $response->setPoints($points);
            $total = $this->choiceTypologieRepository->getTotalBasedOnTypologie(
                (int) $response->getRepondant()->getTypologie()->getId(),
                $response->getRepondant()->isRestauration(),
            );
            $response->setTotal($total);
            $this->entityManager->persist($response);
            $this->entityManager->flush();

            return $this->render('success.html.twig', [
                'form' => $reponseForm,
            ]);
        }

        return $this->render('home.html.twig', [
            'form' => $reponseForm,
        ]);
    }
}
