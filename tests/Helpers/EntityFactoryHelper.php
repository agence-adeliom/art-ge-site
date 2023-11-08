<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use App\Entity\Repondant;
use App\Entity\Reponse;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\TypologieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;

class EntityFactoryHelper {
    public static function generateRepondant(RepondantTest $repondantTest, DepartmentRepository $departmentRepository, TypologieRepository $typologieRepository): Repondant
    {
        $repondant = new Repondant();
        $repondant->setFirstname('John');
        $repondant->setLastname('Doe');
        $repondant->setPhone('0123456879');
        $repondant->setCompany('Adeliom');
        $repondant->setAddress('3 Place de Haguenau');
        $repondant->setCity('Strasbourg');
        $repondant->setZip("67000");
        $repondant->setCountry('France');
        $repondant->setRestauration($repondantTest->isRestauration());
        $repondant->setGreenSpace($repondantTest->isGreenSpace());
        $repondant->setDepartment($repondantTest->getDepartment($departmentRepository));
        $repondant->setTypologie($repondantTest->getTypologie($typologieRepository));
        return $repondant;
    }

    public static function generateReponse(Repondant $repondant, array $thematiques, array $thematiquesPoints, ?int $points = 0): Reponse
    {
        $reponse = new Reponse();
        $reponse->setRepondant($repondant);
        $reponse->setProcessedForm([
            "pointsByQuestions" => array_combine($thematiques, $thematiquesPoints),
            "points" => $points,
        ]);
        $reponse->setCompleted(true);
        $reponse->setCreatedAt(new \DateTimeImmutable());
        $reponse->setSubmittedAt(new \DateTimeImmutable());
        $reponse->setUuid(new Ulid());
        return $reponse;
    }

    public static function getProcessedAnswers(RepondantTest $repondantTest, ChoiceTypologieRepository $choiceTypologieRepository, ChoiceRepository $choiceRepository, TypologieRepository $typologieRepository): array
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [
            'reponse' => [
                'repondant' => [
                    'typologie' => $repondantTest->getTypologie($typologieRepository)->getId(),
                    'restauration' => $repondantTest->isRestauration() ? '1' : '0',
                ],
            ]
        ]));


        $processor = new ProcessedFormReponseDataTransformer($requestStack, $choiceTypologieRepository, $choiceRepository);

        $rawForm = AnswersHelper::generateFullAnswers();
        $processedAnswers = $processor->reverseTransform($rawForm);
        return $processedAnswers;
    }
}
