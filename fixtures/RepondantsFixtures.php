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
        private readonly CityRepository $cityRepository,
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed('artge');
        $this->faker->addProvider(new RepondantProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        $zips = $this->cityRepository->getAllZipCodes();
        $thematiques = $this->thematiqueRepository->findAll();

        for ($i = 0; $i < 100; $i++) {
            $repondant = new Repondant();
            $typologie = $this->faker->typologie();

            $repondant->setEmail($this->faker->email());
            $repondant->setFirstname($this->faker->firstName());
            $repondant->setLastname($this->faker->lastName());
            $repondant->setPhone($this->faker->phoneNumber());
            $repondant->setCompany($this->faker->company());
            $repondant->setAddress($this->faker->address());
            $repondant->setCity($this->faker->city());
            $repondant->setZip($this->faker->randomElement($zips));
            $repondant->setCountry('France');
            $repondant->setRestauration($typologie === 'restaurant' ? true : $this->faker->boolean());
            $repondant->setGreenSpace($this->faker->boolean());
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

            for ($j = 0; $j < 3; $j++) {
                $reponse = new Reponse();
                $reponse->setUuid(Ulid::fromString($this->faker->uuid()));
                $reponse->setRepondant($repondant);
                $date = $this->faker->dateTimeBetween('2023-01-01 00:00:00', '2023-11-01 00:00:00', 'Europe/Paris');
                $reponse->setCreatedAt(\DateTimeImmutable::createFromMutable($date));
                $reponse->setSubmittedAt(\DateTimeImmutable::createFromMutable($date->add(new \DateInterval('PT1H'))));
                $reponse->setCompleted($this->faker->boolean(95));

                $rawForm = [];
                foreach ($thematiques as $thematique) {
                    $question = $thematique->getQuestion();
                    $choices = $this->faker->randomElements($question->getChoices(), null);
                    foreach ($choices as $choice) {
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
                    $manager->persist($score);
                }
                $manager->persist($reponse);
            }
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
