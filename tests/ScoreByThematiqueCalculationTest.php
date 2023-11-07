<?php

namespace App\Tests;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use App\Entity\Choice;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;
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
    public function testSomething(RepondantThematiqueTest $repondantTest): void
    {
        $pointsExpected = [
            'biodiversite-et-conservation-de-la-nature-sur-site' => [
                'hotel' => ['no' => 13, 'yes' => 15],
                'camping' => ['no' => 13, 'yes' => 15],
                'visite' => ['no' => 13, 'yes' => 15],
                'activite' => ['no' => 13, 'yes' => 15],
                'restaurant' => ['yes' => 14]
            ],
            'gestion-des-dechets' => [
                'hotel' => ['no' => 15, 'yes' => 21],
                'camping' => ['no' => 13, 'yes' => 21],
                'visite' => ['no' => 14, 'yes' => 22],
                'activite' => ['no' => 14, 'yes' => 22],
                'restaurant' => ['yes' => 16]
            ],
            'gestion-de-l-eau-et-de-l-erosion' => [
                'hotel' => ['no' => 12, 'yes' => 12],
                'camping' => ['no' => 14, 'yes' => 14],
                'visite' => ['no' => 14, 'yes' => 14],
                'activite' => ['no' => 14, 'yes' => 14],
                'restaurant' => ['yes' => 12]
            ],
            'eco-construction' => [
                'hotel' => ['no' => 13, 'yes' => 13],
                'camping' => ['no' => 13, 'yes' => 13],
                'visite' => ['no' => 13, 'yes' => 13],
                'activite' => ['no' => 13, 'yes' => 13],
                'restaurant' => ['yes' => 13]
            ],
            'gestion-de-l-energie' => [
                'hotel' => ['no' => 21, 'yes' => 22],
                'camping' => ['no' => 18, 'yes' => 20],
                'visite' => ['no' => 20, 'yes' => 22],
                'activite' => ['no' => 20, 'yes' => 21],
                'restaurant' => ['yes' => 19]
            ],
            'entretien-et-proprete' => [
                'hotel' => ['no' => 11, 'yes' => 11],
                'camping' => ['no' => 11, 'yes' => 11],
                'visite' => ['no' => 11, 'yes' => 11],
                'activite' => ['no' => 11, 'yes' => 11],
                'restaurant' => ['yes' => 11]
            ],
            'transport-et-mobilite' => [
                'hotel' => ['no' => 10, 'yes' => 10],
                'camping' => ['no' => 10, 'yes' => 10],
                'visite' => ['no' => 10, 'yes' => 10],
                'activite' => ['no' => 10, 'yes' => 10],
                'restaurant' => ['yes' => 10]
            ],
            'acces-aux-personnes-en-situation-de-handicap' => [
                'hotel' => ['no' => 9, 'yes' => 9],
                'camping' => ['no' => 9, 'yes' => 9],
                'visite' => ['no' => 9, 'yes' => 9],
                'activite' => ['no' => 9, 'yes' => 9],
                'restaurant' => ['yes' => 9]
            ],
            'inclusivite-sociale' => [
                'hotel' => ['no' => 3, 'yes' => 3],
                'camping' => ['no' => 3, 'yes' => 3],
                'visite' => ['no' => 3, 'yes' => 3],
                'activite' => ['no' => 3, 'yes' => 3],
                'restaurant' => ['yes' => 3]
            ],
            'sensibilisation-des-acteurs' => [
                'hotel' => ['no' => 10, 'yes' => 12],
                'camping' => ['no' => 9, 'yes' => 12],
                'visite' => ['no' => 9, 'yes' => 12],
                'activite' => ['no' => 9, 'yes' => 12],
                'restaurant' => ['yes' => 13]
            ],
            'bien-etre-de-l-equipe' => [
                'hotel' => ['no' => 3, 'yes' => 3],
                'camping' => ['no' => 3, 'yes' => 3],
                'visite' => ['no' => 3, 'yes' => 3],
                'activite' => ['no' => 3, 'yes' => 3],
                'restaurant' => ['yes' => 3]
            ],
            'developpement-economique-local' => [
                'hotel' => ['no' => 6, 'yes' => 7],
                'camping' => ['no' => 5, 'yes' => 7],
                'visite' => ['no' => 5, 'yes' => 7],
                'activite' => ['no' => 5, 'yes' => 7],
                'restaurant' => ['yes' => 8]
            ],
            'cooperation-locale-et-liens-avec-les-habitants' => [
                'hotel' => ['no' => 4, 'yes' => 4],
                'camping' => ['no' => 4, 'yes' => 4],
                'visite' => ['no' => 4, 'yes' => 4],
                'activite' => ['no' => 4, 'yes' => 4],
                'restaurant' => ['yes' => 4]
            ],
            'culture-et-patrimoine' => [
                'hotel' => ['no' => 4, 'yes' => 5],
                'camping' => ['no' => 4, 'yes' => 5],
                'visite' => ['no' => 4, 'yes' => 5],
                'activite' => ['no' => 4, 'yes' => 5],
                'restaurant' => ['yes' => 6]
            ],
        ];


        $rawForm = [];
        $rawForm[1]['answers'][1] = 'on';
        $rawForm[1]['answers'][2] = 'on';
        $rawForm[1]['answers'][3] = 'on';
        $rawForm[1]['answers'][4] = 'on';
        $rawForm[1]['answers'][5] = 'on';
        $rawForm[1]['answers'][6] = 'on';
        $rawForm[1]['answers'][7] = 'on';
        $rawForm[1]['answers'][8] = 'on';
        $rawForm[1]['answers'][9] = 'on';
        $rawForm[1]['answers'][10] = 'on';
        $rawForm[1]['answers'][11] = 'on';
        $rawForm[1]['answers'][12] = 'on';
//        $rawForm[1]['answers'][13] = 'on';
        $rawForm[2]['answers'][14] = 'on';
        $rawForm[2]['answers'][15] = 'on';
        $rawForm[2]['answers'][16] = 'on';
        $rawForm[2]['answers'][17] = 'on';
        $rawForm[2]['answers'][18] = 'on';
        $rawForm[2]['answers'][19] = 'on';
        $rawForm[2]['answers'][20] = 'on';
        $rawForm[2]['answers'][21] = 'on';
        $rawForm[2]['answers'][22] = 'on';
        $rawForm[2]['answers'][23] = 'on';
        $rawForm[2]['answers'][24] = 'on';
        $rawForm[2]['answers'][25] = 'on';
        $rawForm[2]['answers'][26] = 'on';
//        $rawForm[2]['answers'][27] = 'on';
//        $rawForm[3]['answers'][28] = 'on';
//        $rawForm[3]['answers'][29] = 'on';
//        $rawForm[3]['answers'][30] = 'on';
//        $rawForm[3]['answers'][31] = 'on';
//        $rawForm[3]['answers'][32] = 'on';
//        $rawForm[3]['answers'][33] = 'on';
//        $rawForm[3]['answers'][34] = 'on';
//        $rawForm[3]['answers'][35] = 'on';
//        $rawForm[3]['answers'][36] = 'on';
//        $rawForm[3]['answers'][37] = 'on';
//        $rawForm[4]['answers'][38] = 'on';
//        $rawForm[4]['answers'][39] = 'on';
//        $rawForm[4]['answers'][40] = 'on';
//        $rawForm[4]['answers'][41] = 'on';
//        $rawForm[4]['answers'][42] = 'on';
//        $rawForm[4]['answers'][43] = 'on';
//        $rawForm[4]['answers'][44] = 'on';
//        $rawForm[4]['answers'][45] = 'on';
//        $rawForm[5]['answers'][46] = 'on';
//        $rawForm[5]['answers'][47] = 'on';
//        $rawForm[5]['answers'][48] = 'on';
//        $rawForm[5]['answers'][49] = 'on';
//        $rawForm[5]['answers'][50] = 'on';
//        $rawForm[5]['answers'][51] = 'on';
//        $rawForm[5]['answers'][52] = 'on';
//        $rawForm[5]['answers'][53] = 'on';
//        $rawForm[5]['answers'][54] = 'on';
//        $rawForm[5]['answers'][55] = 'on';
//        $rawForm[5]['answers'][56] = 'on';
//        $rawForm[5]['answers'][57] = 'on';
//        $rawForm[5]['answers'][58] = 'on';
//        $rawForm[5]['answers'][59] = 'on';
//        $rawForm[6]['answers'][60] = 'on';
//        $rawForm[6]['answers'][61] = 'on';
//        $rawForm[6]['answers'][62] = 'on';
//        $rawForm[6]['answers'][63] = 'on';
//        $rawForm[6]['answers'][64] = 'on';
//        $rawForm[6]['answers'][65] = 'on';
//        $rawForm[7]['answers'][66] = 'on';
//        $rawForm[7]['answers'][67] = 'on';
//        $rawForm[7]['answers'][68] = 'on';
//        $rawForm[7]['answers'][69] = 'on';
//        $rawForm[7]['answers'][70] = 'on';
//        $rawForm[7]['answers'][71] = 'on';
//        $rawForm[7]['answers'][72] = 'on';
//        $rawForm[7]['answers'][73] = 'on';
//        $rawForm[8]['answers'][74] = 'on';
//        $rawForm[8]['answers'][75] = 'on';
//        $rawForm[8]['answers'][76] = 'on';
//        $rawForm[8]['answers'][77] = 'on';
//        $rawForm[8]['answers'][78] = 'on';
//        $rawForm[8]['answers'][79] = 'on';
//        $rawForm[8]['answers'][80] = 'on';
//        $rawForm[8]['answers'][81] = 'on';
//        $rawForm[8]['answers'][82] = 'on';
//        $rawForm[9]['answers'][83] = 'on';
//        $rawForm[9]['answers'][84] = 'on';
//        $rawForm[9]['answers'][85] = 'on';
//        $rawForm[9]['answers'][86] = 'on';
//        $rawForm[10]['answers'][87] = 'on';
//        $rawForm[10]['answers'][88] = 'on';
//        $rawForm[10]['answers'][89] = 'on';
//        $rawForm[10]['answers'][90] = 'on';
//        $rawForm[10]['answers'][91] = 'on';
//        $rawForm[10]['answers'][92] = 'on';
//        $rawForm[10]['answers'][93] = 'on';
//        $rawForm[10]['answers'][94] = 'on';
//        $rawForm[11]['answers'][95] = 'on';
//        $rawForm[11]['answers'][96] = 'on';
//        $rawForm[11]['answers'][97] = 'on';
//        $rawForm[11]['answers'][98] = 'on';
//        $rawForm[11]['answers'][99] = 'on';
//        $rawForm[12]['answers'][100] = 'on';
//        $rawForm[12]['answers'][101] = 'on';
//        $rawForm[12]['answers'][102] = 'on';
//        $rawForm[12]['answers'][103] = 'on';
//        $rawForm[12]['answers'][104] = 'on';
//        $rawForm[13]['answers'][105] = 'on';
//        $rawForm[13]['answers'][106] = 'on';
//        $rawForm[13]['answers'][107] = 'on';
//        $rawForm[13]['answers'][108] = 'on';
//        $rawForm[13]['answers'][109] = 'on';
//        $rawForm[14]['answers'][110] = 'on';
//        $rawForm[14]['answers'][111] = 'on';
//        $rawForm[14]['answers'][112] = 'on';
//        $rawForm[14]['answers'][113] = 'on';
//        $rawForm[14]['answers'][114] = 'on';
//        $rawForm[14]['answers'][115] = 'on';
//        $rawForm[15]['answers'][116] = 'on';
//        $rawForm[15]['answers'][117] = 'on';
//        $rawForm[15]['answers'][118] = 'on';
//        $rawForm[15]['answers'][119] = 'on';
//        $rawForm[15]['answers'][120] = 'on';
//        $rawForm[15]['answers'][121] = 'on';
//        $rawForm[15]['answers'][122] = 'on';
//        $rawForm[15]['answers'][123] = 'on';
//        $rawForm[15]['answers'][124] = 'on';
//        $rawForm[15]['answers'][125] = 'on';
//        $rawForm[15]['answers'][126] = 'on';
//        $rawForm[15]['answers'][127] = 'on';
//        $rawForm[15]['answers'][128] = 'on';
//        $rawForm[15]['answers'][129] = 'on';
//        $rawForm[15]['answers'][130] = 'on';
//        $rawForm[15]['answers'][131] = 'on';
//        $rawForm[15]['answers'][132] = 'on';
//        $rawForm[15]['answers'][133] = 'on';
//        $rawForm[15]['answers'][134] = 'on';
//        $rawForm[15]['answers'][135] = 'on';
//        $rawForm[15]['answers'][136] = 'on';
//        $rawForm[15]['answers'][137] = 'on';
//        $rawForm[15]['answers'][138] = 'on';
//        $rawForm[15]['answers'][139] = 'on';
//        $rawForm[15]['answers'][140] = 'on';
//        $rawForm[15]['answers'][141] = 'on';
//        $rawForm[15]['answers'][142] = 'on';
//        $rawForm[15]['answers'][143] = 'on';
//        $rawForm[15]['answers'][144] = 'on';

        /** @var ReponseScoreGeneration $reponseScoreGeneration */
        $reponseScoreGeneration = static::getContainer()->get(ReponseScoreGeneration::class);
        $departmentRepository = static::getContainer()->get(DepartmentRepository::class);
        $typologieRepository = static::getContainer()->get(TypologieRepository::class);
        /** @var ThematiqueRepository $thematiqueRepository */
        $thematiqueRepository = static::getContainer()->get(ThematiqueRepository::class);
        /** @var ChoiceTypologieRepository $choiceTypologieRepository */
        $choiceTypologieRepository = static::getContainer()->get(ChoiceTypologieRepository::class);

        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [
            'reponse' => [
                'repondant' => [
                    'typologie' => $repondantTest->getTypologie($typologieRepository)->getId(),
                    'restauration' => $repondantTest->isRestauration() ? '1' : '0',
                ],
            ]
        ]));

        $processor = new ProcessedFormReponseDataTransformer($requestStack, $choiceTypologieRepository);
//        $answers = json_decode('{"1":{"answers":{"2":"on","3":"on","4":"on"}},"2":{"answers":{"14":"on","15":"on","16":"on"}}}', true);

        $processedAnswers = $processor->reverseTransform($rawForm);
//        dd($processedAnswers);

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
        foreach ($thematiquesPoints as $key => $maxPointsPossible){
            if (isset($processedAnswers['pointsByQuestions'][$key])) {
                assertEquals($maxPointsPossible, $processedAnswers['pointsByQuestions'][$key]);
            }
        }
    }

    public function scoreProvider(): iterable
    {
//        $departmentRepository = static::getContainer()->get(DepartmentRepository::class);
//        $departments = $departmentRepository->findAll();


        $repondant = new RepondantThematiqueTest(typologie: 'hotel', restauration: true);
        yield $repondant->getDataSetName()  => [$repondant];

//        foreach (['hotel', 'camping', 'visite', 'activite'] as $typologie) {
//            $repondant = new RepondantThematiqueTest(typologie: $typologie, restauration: true);
//            yield $repondant->getDataSetName()  => [$repondant];
//
//            $repondant = new RepondantThematiqueTest(typologie: $typologie, restauration: false);
//            yield $repondant->getDataSetName()  => [$repondant];
//        }
//
//        $repondant = new RepondantThematiqueTest(typologie: 'restaurant', restauration: true);
//        yield $repondant->getDataSetName()  => [$repondant];
    }
}

class RepondantThematiqueTest {
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
