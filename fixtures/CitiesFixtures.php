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
            $citiesImported = [];

            foreach ($citiesDatas as $c){
                if (in_array($c['codeINSEE_commuune'], $citiesImported)) {
                    continue;
                }
                $city = new City();
                $name = trim($c['Nom_de_la_commune']);
                $city->setName($name);
                $city->setSlug($slugger->slug(strtolower($name))->toString());
                $city->setZip($c['Code_Postal']);
                $city->setInsee($c['codeINSEE_commuune']);
                $citiesImported[] = $city->getInsee();
                $manager->persist($city);
            }
            $manager->flush();
        }

    }
}
