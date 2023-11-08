<?php

namespace App\Tests;

use App\Entity\Score;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseScoreGeneration;
use App\Tests\Helpers\EntityFactoryHelper;
use App\Tests\Helpers\RepondantTest;
use App\ValueObject\RepondantTypologie;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @internal
 *
 * @coversNothing
 */
class ScoreCalculationTest extends KernelTestCase
{
    /**
     * @dataProvider scoreProvider
     */
    public function testSomething(RepondantTest $repondantTest, $expectedTotal): void
    {
        /**
         * RECUPERATION DES SERVICES NECESSAIRE AU FONCTIONNEMENT DES TESTS
         **/
        /** @var ReponseScoreGeneration $reponseScoreGeneration */
        $reponseScoreGeneration = static::getContainer()->get(ReponseScoreGeneration::class);
        $departmentRepository = static::getContainer()->get(DepartmentRepository::class);
        $typologieRepository = static::getContainer()->get(TypologieRepository::class);
        /** @var ThematiqueRepository $thematiqueRepository */
        $thematiqueRepository = static::getContainer()->get(ThematiqueRepository::class);
        /** @var ChoiceTypologieRepository $choiceTypologieRepository */
        $choiceTypologieRepository = static::getContainer()->get(ChoiceTypologieRepository::class);

        /**
         * GENERATION DES VALEURS ATTENDUES
         **/
        $thematiques = [1,2];
        $thematiquesPoints = [3,2];

        $repondant = EntityFactoryHelper::generateRepondant($repondantTest, $departmentRepository, $typologieRepository);
        $reponse = EntityFactoryHelper::generateReponse($repondant, $thematiques, $thematiquesPoints);

        /**
         * GENERATION DES VALEURS REELLES CALCULEES PAR LE CODE
         **/
        $scoreGeneration = $reponseScoreGeneration->generateScore($reponse);


        /**
         * GENERATION DES SCORES ATTENDUS
         **/
        $reponse->setPoints($scoreGeneration->getPoints());
        $reponse->setTotal($scoreGeneration->getTotal());
        $expectedScores = [];
        foreach ($thematiques as $key => $thematiqueId){
            $score = new Score();
            $score->setReponse($reponse);
            $score->setPoints($thematiquesPoints[$key]);
            $score->setThematique($thematiqueRepository->find($thematiqueId));
            $score->setTotal($choiceTypologieRepository->getPonderationByQuestionAndTypologie($thematiqueId, RepondantTypologie::fromRepondant($reponse->getRepondant())));
            $expectedScores[] = $score;
        }

        /**
         * VERIFICATION DE CHAQUE VALEUR
         **/
        assertEquals($expectedTotal, $scoreGeneration->getTotal());
        assertEquals($expectedScores, $scoreGeneration->getScores());
    }

    public function scoreProvider(): iterable
    {
        $pointsExpected = [
            'hotel' => [
                'withoutRestauration' => 134,
                'withRestauration' => 147,
            ],
            'camping' => [
                'withoutRestauration' => 129,
                'withRestauration' => 147,
            ],
            'visite' => [
                'withoutRestauration' => 132,
                'withRestauration' => 150,
            ],
            'activite' => [
                'withoutRestauration' => 132,
                'withRestauration' => 149,
            ],
            'restaurant' => [
                'withRestauration' => 141,
            ],
        ];

        foreach (['hotel', 'camping', 'visite', 'activite'] as $typologie) {
            $repondant = new RepondantTest(typologie: $typologie, restauration: true);
            yield $repondant->getDataSetName()  => [$repondant, $pointsExpected[$typologie]['withRestauration']];

            $repondant = new RepondantTest(typologie: $typologie, restauration: false);
            yield $repondant->getDataSetName()  => [$repondant, $pointsExpected[$typologie]['withoutRestauration']];
        }

        $repondant = new RepondantTest(typologie: 'restaurant', restauration: true);
        yield $repondant->getDataSetName()  => [$repondant, $pointsExpected['restaurant']['withRestauration']];
    }
}
