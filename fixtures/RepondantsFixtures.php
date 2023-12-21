<?php

declare(strict_types=1);

namespace DataFixtures;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use App\Entity\Reponse;
use App\Entity\Thematique;
use App\Message\ReponseConfirmationMessage;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\QuestionChoiceExcluder;
use App\Services\ReponseScoreGeneration;
use DataFixtures\Provider\RepondantProvider;
use App\Entity\Repondant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;

class RepondantsFixtures extends Fixture implements DependentFixtureInterface
{
    private readonly Generator $faker;

    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly ChoiceRepository $choiceRepository,
        private readonly ReponseScoreGeneration $reponseScoreGeneration,
        private readonly QuestionChoiceExcluder $questionChoiceExcluder,
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed('artge');
        $this->faker->addProvider(new RepondantProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        $thematiques = $this->thematiqueRepository->findAll();

        $repondantDatas = [
            ['NATALIE', 'RECEPTION', '0389426476', 'reception@camping-mulhouse.com', "CAMPING DE L'ILL OTC", '1 rue Pierre de Coubertin', '68100', 'MULHOUSE', 'camping', false, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    0,0,0,1,0,0,0,0,0,0,0,0,0,
                    0,1,0,0,1,0,0,0,0,0,0,0,0,0,
                    1,0,1,0,0,0,0,1,0,0,
                    0,0,0,0,0,0,0,1,
                    0,0,0,1,0,0,0,0,0,0,0,1,0,0,
                    0,1,0,0,0,0,
                    0,0,0,1,0,0,1,0,
                    0,0,0,0,0,0,0,0,1,
                    1,1,0,0,
                    0,0,0,1,0,0,0,0,
                    0,0,0,1,0,
                    0,1,0,0,0,
                    0,1,0,0,0,
                    0,0,0,0,0,1,
                    0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0
                ]
            ],
//            ['Julien', 'LUTZ', '03750616237', 'H0627-GM@accor.com', "CLR hotels Hotels Ibis", '34 ALLEE NATHAN KATZ, RUE DES CEVENNES', '68100', 'SAUSHEIM MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['Farid', 'Sellemet', '0389562362', 'reception@aubergejeunesse-mulhouse.com', "Auberge de Jeunesse", "37 rue de l'Illberg", '68200', 'MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['SYLVAIN', 'VERNEREY', '0688745404', 'vernerey@citedutrain.com', "CITE DU TRAIN/PATRIMOINE SNCF", '2 RUE ALFRED DE GLEHN', '68200', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['Marie', 'Basenach', '0634315287', 'marie.basenach@mulhouse-alsace.fr', "Parc zoologique et botanique de Mulhouse", '111 avenue de la 1ère Division Blindée', '68100', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['Guillaume', 'GASSER', '0664981214', 'g.gasser@museedelauto.org', "Musee national automobile", '192, avenue de colmar', '68100', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['gaetan', 'loeb', '0777737468', 'loeb.gaetan@gmail.com', "BEST WESTERN PLUS Hôtel **** Au Cheval Blanc", '27 rue principale', '68390', 'MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
//            ['MARIE', 'GUTZWILLER', '0675666838', 'info@hotelbristol.com', "HOTEL BRISTOL", '18 AVENUE DE COLMAR', '68390', 'MULHOUSE', 'hotel', true, false, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
//            ],
        ];

        foreach ($repondantDatas as $repondantData) {
            $repondant = new Repondant();
            $typologie = $repondantData[8];

            $repondant->setFirstname($repondantData[0]);
            $repondant->setLastname($repondantData[1]);
            $repondant->setPhone($repondantData[2]);
            $repondant->setEmail($repondantData[3]);
            $repondant->setCompany($repondantData[4]);
            $repondant->setAddress($repondantData[5]);
            $repondant->setZip($repondantData[6]);
            $repondant->setCity($repondantData[7]);
            $repondant->setCountry('France');
            $repondant->setRestauration($repondantData[9]);
            $repondant->setGreenSpace($repondantData[10]);
            $repondant->setDepartment($this->departmentRepository->findOneBy(['slug' => $repondant->getZip() === '68000' ? 'haut-rhin' : 'bas-rhin']));
            $repondant->setTypologie($this->typologieRepository->findOneBy(['slug' => $typologie]));
            $manager->persist($repondant);

            $requestStack = new RequestStack();
            $requestStack->push(new Request([], [
                'reponse' => [
                    'repondant' => [
                        'typologie' => $repondant->getTypologie()->getId(),
                        'restauration' => $repondant->isRestauration() ? '1' : '0',
                    ],
                ]
            ]));

            $reponse = new Reponse();
            $reponse->setUuid(Ulid::fromString($this->faker->uuid()));
            $reponse->setRepondant($repondant);
            $reponse->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $repondantData[11]));
            $reponse->setSubmittedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $repondantData[12]));
            $reponse->setCompleted(true);

            $choices = $repondantData[13];
            $rawForm = [];
            $choiceId = 1;
            foreach ($thematiques as $thematique) {
                $question = $thematique->getQuestion();
                if (!$repondant->isGreenSpace()) {
                    $question = $this->questionChoiceExcluder->excludeChoices($question);
                }
                $questionChoices = array_splice($choices, 0, $question->getChoices()->count());
                foreach ($questionChoices as $choice) {
                    if ($choice === 1) {
                        $rawForm[$question->getId()]['answers'][$choiceId] = 'on';
                    }
                    $choiceId++;
                }
            }
            $reponse->setRawForm($rawForm);

            $processor = new ProcessedFormReponseDataTransformer($requestStack, $this->choiceTypologieRepository, $this->choiceRepository, $this->thematiqueRepository);
            $processedAnswers = $processor->reverseTransform($reponse->getRawForm());
            $reponse->setProcessedForm($processedAnswers);

            $scoreGeneration = $this->reponseScoreGeneration->generateScore($reponse);
            $reponse->setPoints($scoreGeneration->getPoints());
            $reponse->setTotal($scoreGeneration->getTotal());
            foreach ($scoreGeneration->getScores() as $score) {
                $reponse->addScore($score);
                $manager->persist($score);
            }
            $manager->persist($reponse);
            //            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ChoiceTypologiesFixtures::class,
            CitiesFixtures::class,
        ];
    }
}
