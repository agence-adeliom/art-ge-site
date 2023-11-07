<?php

namespace App\Tests;

use App\Entity\Department;
use App\Entity\Repondant;
use App\Entity\Reponse;
use App\Entity\Score;
use App\Entity\Typologie;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseScoreGeneration;
use App\ValueObject\RepondantTypologie;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;
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
        /** @var ReponseScoreGeneration $reponseScoreGeneration */
        $reponseScoreGeneration = static::getContainer()->get(ReponseScoreGeneration::class);
        $departmentRepository = static::getContainer()->get(DepartmentRepository::class);
        $typologieRepository = static::getContainer()->get(TypologieRepository::class);
        /** @var ThematiqueRepository $thematiqueRepository */
        $thematiqueRepository = static::getContainer()->get(ThematiqueRepository::class);
        /** @var ChoiceTypologieRepository $choiceTypologieRepository */
        $choiceTypologieRepository = static::getContainer()->get(ChoiceTypologieRepository::class);

        $repondant = new Repondant();
        $repondant->setFirstname('John');
        $repondant->setLastname('Doe');
        $repondant->setPhone('0123456879');
        $repondant->setCompany('Adeliom');
        $repondant->setAddress('3 Place de Haguenau');
        $repondant->setCity('Strasbourg');
        $repondant->setZip(67000);
        $repondant->setCountry('France');
        $repondant->setRestauration($repondantTest->isRestauration());
        $repondant->setGreenSpace($repondantTest->isGreenSpace());
        $repondant->setDepartment($repondantTest->getDepartment($departmentRepository));
        $repondant->setTypologie($repondantTest->getTypologie($typologieRepository));

        $thematiques = [1,2];
        $thematiquesPoints = [3,2];

        $reponse = new Reponse();
        $reponse->setRepondant($repondant);
        $reponse->setProcessedForm([
            "pointsByQuestions" => array_combine($thematiques, $thematiquesPoints),
            "points" => 5,
        ]);
        $reponse->setCompleted(true);
        $reponse->setCreatedAt(new \DateTimeImmutable());
        $reponse->setSubmittedAt(new \DateTimeImmutable());
        $reponse->setUuid(new Ulid());
        $scoreGeneration = $reponseScoreGeneration->generateScore($reponse);
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
        assertEquals($expectedTotal, $scoreGeneration->getTotal());
        assertEquals($expectedScores, $scoreGeneration->getScores());
    }

    public function scoreProvider(): iterable
    {
//        $departmentRepository = static::getContainer()->get(DepartmentRepository::class);
//        $departments = $departmentRepository->findAll();

        $pointsExpected = [
            'hotel' => [
                'withRestauration' => 147,
                'withoutRestauration' => 134,
            ],
            'camping' => [
                'withRestauration' => 147,
                'withoutRestauration' => 129,
            ],
            'visite' => [
                'withRestauration' => 150,
                'withoutRestauration' => 132,
            ],
            'activite' => [
                'withRestauration' => 149,
                'withoutRestauration' => 132,
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

class RepondantTest {
    private string $typologie;
    private bool $restauration;
    private bool $greenSpace;
    private string $department;

    public function __construct(string $typologie, bool $restauration, ?string $department = 'ardennes', ?bool $greenSpace = true)
    {
        $this->typologie = $typologie;
        $this->restauration = $restauration;
        $this->greenSpace = $greenSpace;
        $this->department = $department;
    }

    public function getTypologie(TypologieRepository $typologieRepository): Typologie
    {
        return $typologieRepository->findOneBy(['slug' => $this->typologie]);
    }

    public function isRestauration(): bool
    {
        return $this->restauration;
    }

    public function isGreenSpace(): bool
    {
        return $this->greenSpace;
    }

    public function getDepartment(DepartmentRepository $departmentRepository): Department
    {
        return $departmentRepository->findOneBy(['slug' => $this->department]);
    }

    public function getDataSetName(): string
    {
        return sprintf('%s %s restauration %s espace vert dans %s',
            $this->typologie,
            $this->restauration ? 'avec' : 'sans',
            $this->greenSpace ? 'avec' : 'sans',
            $this->department,
        );
    }
}
