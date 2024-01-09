<?php

declare(strict_types=1);

namespace DataFixtures;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use App\Entity\Reponse;
use App\Enum\ThematiqueSlugEnum;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
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
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        'je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques',
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        'j-ai-supprime-les-plastiques-a-usage-unique-et-tous-mes-emballages-plastiques-sont-en-grands-formats-superieur-a-5l',
                        'les-visiteurs-les-clients-peuvent-trier-leurs-dechets-et-j-ai-des-filieres-de-valorisation-en-place-pour-les-emballages',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        'j-evite-l-artificialisation-des-parkings-je-privilegie-le-revetement-poreux-les-espaces-bitumes-sont-limites-aux-voies-de-circulation-de-lourds-vehicules-et-pour-les-places-pmr',
                        'je-recycle-l-eau-de-certains-usages-rincage-eau-de-cuisson-carafes-d-eau-pour-l-arrosage-exterieur',
                        'j-arrose-les-vegetaux-en-pleine-terre-uniquement-lors-de-leur-premiere-annee-et-je-n-arrose-pas-le-gazon',
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        'je-n-ai-pas-de-climatisation',
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        'j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau',
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        'je-communique-des-informations-claires-sur-les-transports-collectifs-le-covoiturage-les-transports-publics-pour-encourager-a-venir-sans-voiture',
                        'je-communique-des-instructions-claires-pour-realiser-des-activites-autour-de-mon-site-sans-voiture',
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        'je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees',
                        'j-accepte-les-cheques-vacances-ancv',
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        'au-moins-80-de-mes-fournisseurs-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km',
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        'je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune',
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'accueil-velo',
                    ],
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

            $rawForm = [];
            foreach ($repondantData[13] as $thematiqueSlug => $repondantChoicesSlugs) {
                $thematique = $this->thematiqueRepository->findOneBy(['slug' => $thematiqueSlug]);
                $question = $thematique->getQuestion();

                foreach ($repondantChoicesSlugs as $repondantChoicesSlug){
                    $choice = $this->choiceRepository->findOneBy(['question' => $question->getId(),'slug' => $repondantChoicesSlug]);
                    $rawForm[$question->getId()]['answers'][$choice->getId()] = 'on';
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
