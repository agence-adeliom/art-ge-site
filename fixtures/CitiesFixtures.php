<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CitiesFixtures extends Fixture
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();
        /** @var string $datasDirectory */
        $datasDirectory = $this->parameterBag->get('datas_directory');
        $citiesFile = file_get_contents($datasDirectory . '/ExportCPxCOMMUNES_nov2023.txt');
        if ($citiesFile) {
            $csvEncoder = new CsvEncoder(['csv_delimiter' => '	']);
            /** @var array{Code_Postal: string, codeINSEE_commuune: string, Nom_de_la_commune: string} $citiesDatas */
            $citiesDatas = $csvEncoder->decode($citiesFile, 'csv');

            foreach ($citiesDatas as $c){
                $city = new City();
                $name = $c['Nom_de_la_commune'];
                $city->setName($name);
                $city->setSlug($slugger->slug(strtolower((string) $name))->toString());
                $city->setZip($c['Code_Postal']);
                $city->setInsee($c['codeINSEE_commuune']);
                $manager->persist($city);
            }
            $manager->flush();
        }

    }
}
