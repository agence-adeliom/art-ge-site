<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Reponse;
use App\Repository\DepartmentRepository;
use App\Repository\TypologieRepository;
use DataFixtures\Provider\RepondantProvider;
use App\Entity\Repondant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Uid\Ulid;

class RepondantsFixtures extends Fixture
{
    private readonly Generator $faker;

    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly TypologieRepository $typologieRepository,
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed('artge');
        $this->faker->addProvider(new RepondantProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $repondant = new Repondant();

            $repondant->setEmail($this->faker->email());
            $repondant->setFirstname($this->faker->firstName());
            $repondant->setLastname($this->faker->lastName());
            $repondant->setPhone($this->faker->phoneNumber());
            $repondant->setCompany($this->faker->company());
            $repondant->setAddress($this->faker->address());
            $repondant->setCity($this->faker->city());
            $repondant->setZip($this->faker->postcode());
            $repondant->setCountry('France');
            $repondant->setRestauration($this->faker->boolean());
            $repondant->setGreenSpace($this->faker->boolean());
            $repondant->setDepartment($this->departmentRepository->findOneBy(['slug' => $this->faker->departement()]));
            $repondant->setTypologie($this->typologieRepository->findOneBy(['slug' => $this->faker->typologie()]));
            $manager->persist($repondant);

            $ponderationTotals = [134,147,129,147,132,150,132,149,141];
            for ($j = 0; $j < 3; $j++) {
                $reponse = new Reponse();
                $reponse->setUuid(Ulid::fromString($this->faker->uuid()));
                $reponse->setRepondant($repondant);
                $date = $this->faker->dateTimeBetween('2023-01-01 00:00:00', '2023-11-01 00:00:00', 'Europe/Paris');
                $reponse->setCreatedAt(\DateTimeImmutable::createFromMutable($date));
                $reponse->setSubmittedAt(\DateTimeImmutable::createFromMutable($date->add(new \DateInterval('PT1H'))));
                $reponse->setCompleted($this->faker->boolean(95));
                $reponse->setPoints($this->faker->numberBetween(0, min($ponderationTotals)));
                $reponse->setTotal($this->faker->randomElement($ponderationTotals));
                $manager->persist($reponse);
            }
        }

        $manager->flush();
    }
}
