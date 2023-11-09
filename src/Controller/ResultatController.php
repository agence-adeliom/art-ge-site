<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Reponse;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    /** @return array<mixed> */
    #[Route('/resultat/{uuid}', name: 'app_resultat_single')]
    #[Template('resultat.html.twig')]
    public function __invoke(Reponse $reponse): array
    {
        $rawForm = $reponse->getRawForm();
        foreach ($reponse->getScores() as $score) {
            $thematique = $score->getThematique();
            if (isset($rawForm[$thematique->getId()]['answers'])) {
                $choices = $thematique->getQuestion()->getChoices();
                $answers = array_keys(array_filter($rawForm[$thematique->getId()]['answers'], function (string $answer) {
                    return 'on' === $answer;
                }));

                $chosenChoices = [];
                $notChosenChoices = [];
                $choices->map(function (Choice $choice) use ($answers, &$chosenChoices, &$notChosenChoices): void {
                    if (in_array($choice->getId(), $answers)) {
                        $chosenChoices[] = $choice;
                    } else {
                        $notChosenChoices[] = $choice;
                    }
                });

                $score->setChosenChoices($chosenChoices);
                $score->setNotChosenChoices($notChosenChoices);
            }
        }

        return [
            'reponse' => $reponse,
        ];
    }
}
