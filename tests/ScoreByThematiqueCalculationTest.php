<?php

namespace App\Tests;

use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseScoreGeneration;
use App\Tests\Helpers\EntityFactoryHelper;
use App\Tests\Helpers\PonderationHelper;
use App\Tests\Helpers\RepondantTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @internal
 *
 * @coversNothing
 */
class ScoreByThematiqueCalculationTest extends KernelTestCase
{
    /**
     * @dataProvider scoreProvider
     */
    public function testSomething(RepondantTest $repondantTest): void
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
        /** @var ChoiceRepository $choiceRepository */
        $choiceRepository = static::getContainer()->get(ChoiceRepository::class);

        /**
         * GENERATION DES VALEURS ATTENDUES
         **/
        $pointsExpected = PonderationHelper::getExpectedPonderationsByThematiquesAndTypologiesAndRestauration();
        $thematiques = [];
        $thematiquesPoints = [];

        foreach ($thematiqueRepository->findAll() as $thematique) {
            if ($thematique->getSlug() === 'labels') {
                continue;
            }
            $thematiques[] = $thematique->getId();
            $repondantTypologieSlug = $repondantTest->getTypologie($typologieRepository)->getSlug();
            $repondantRestauration = $repondantTest->isRestauration() ? 'yes' : 'no';
            $thematiquesPoints[$thematique->getId()] = $pointsExpected[$thematique->getSlug()][$repondantTypologieSlug][$repondantRestauration];
        }

        $repondant = EntityFactoryHelper::generateRepondant($repondantTest, $departmentRepository, $typologieRepository);
        $reponse = EntityFactoryHelper::generateReponse($repondant, $thematiques, $thematiquesPoints);
        $scoreGeneration = $reponseScoreGeneration->generateScore($reponse);
        $reponse->setPoints($scoreGeneration->getPoints());
        $reponse->setTotal($scoreGeneration->getTotal());



        /**
         * GENERATION DES VALEURS REELLES CALCULEES PAR LE CODE
         **/
        $processedAnswers = EntityFactoryHelper::getProcessedAnswers($repondantTest, $choiceTypologieRepository, $choiceRepository, $thematiqueRepository, $typologieRepository);


        /**
         * VERIFICATION DE CHAQUE VALEUR
         **/
        foreach ($thematiquesPoints as $key => $maxPointsPossible){
            assertEquals($maxPointsPossible, $processedAnswers['pointsByQuestions'][$key]);
        }
    }

    public function scoreProvider(): iterable
    {
        foreach (['hotel', 'camping', 'visite', 'activite'] as $typologie) {
            $repondant = new RepondantTest(typologie: $typologie, restauration: true);
            yield $repondant->getDataSetName()  => [$repondant];

            $repondant = new RepondantTest(typologie: $typologie, restauration: false);
            yield $repondant->getDataSetName()  => [$repondant];
        }

        $repondant = new RepondantTest(typologie: 'restaurant', restauration: true);
        yield $repondant->getDataSetName()  => [$repondant];
    }
}
