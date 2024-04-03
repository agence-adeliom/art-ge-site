<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\City;
use App\Entity\Department;
use App\Entity\Epci;
use App\Entity\Territoire;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Ulid;

class TerritoireFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(
        private readonly CityRepository $cityRepository,
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $allCities = [];
        foreach ($this->cityRepository->findAll() as $c) {
            $allCities[$c->getInsee()] = $c;
        }

        $slugger = new AsciiSlugger();
        /** @var string $datasDirectory */
        $datasDirectory = $this->parameterBag->get('datas_directory');
        $ecpiFile = file_get_contents($datasDirectory . '/OT_EPCI_dec2024.csv');
        if ($ecpiFile) {
            $csvEncoder = new CsvEncoder(['csv_delimiter' => ',']);
            /** @var array{INSEE_commune: string, NOM_COMMUNES: string, Nom_EPCI: string} $ecpiDatas */
            $ecpiDatas = $csvEncoder->decode($ecpiFile, 'csv');

            /** IMPORT DE LA REGION ET DES DEPARTEMENTS */
            $region = new Territoire();
            $region->setUuid(new Ulid());
            $region->setName('Grand-Est');
            $region->setSlug('grand-est');
            $region->setUseSlug(true);
            $region->setIsPublic(true);
            $region->setArea(TerritoireAreaEnum::REGION);
            $manager->persist($region);

            $departements = [];
            foreach (DepartementEnum::cases() as $departementEnum) {
                $departementCode = DepartementEnum::getCode($departementEnum);
                $departement = new Territoire();
                $departement->setUuid(new Ulid());
                $departement->setName(DepartementEnum::getLabel($departementEnum));
                $departement->setSlug($departementEnum->value);
                $departement->setUseSlug(true);
                $departement->setIsPublic(true);
                $departement->addParent($region);
                $departement->setArea(TerritoireAreaEnum::DEPARTEMENT);
                $departements[$departementCode] = $departement;
                $manager->persist($departement);
            }

            /** STOCKAGES DES INSEE PAR SIREN D'EPCI */
            $cityCodes = [];
            $ot2s = [];
            foreach ($ecpiDatas as $e) {
                $inseeCommune = str_pad((string) $e['INSEE_commune'], 5, '0', STR_PAD_LEFT);
                $sirenEPCI = $e['sirenEPCI'];
                if (!array_key_exists($sirenEPCI, $cityCodes)) {
                    $cityCodes[$sirenEPCI] = [];
                }
                $city = $this->cityRepository->findOneBy(['insee' => $inseeCommune]);
                if (!$city) {
                    dump($inseeCommune);
                }
                if ($city) {
                    $cityCodes[$sirenEPCI][] = $city;
                }
                if ($e['Office de tourisme 2'] !== '') {
                    $ot2s[$e['Office de tourisme 2']][] = $inseeCommune;
                }
            }

            /** STOCKAGES DES CODE DEPARTEMENT PAR SIREN D'EPCI */
            $departementsCodes = [];
            foreach ($cityCodes as $sirenEPCI => $cities) {
                $departementsCodes[$sirenEPCI] = array_values(array_unique(array_map(fn (City $city): string => substr($city->getInsee(), 0, 2), $cities)));
            }

            /** IMPORT DES EPCIS ET TERRITOIRES */
            $ecpisImported = [];
            $territoiresImported = [];
            foreach ($ecpiDatas as $e) {
                /** IMPORT DES EPCIS */
                $nameEPCI = $e['Nom_EPCI'];
                $sirenEPCI = $e['sirenEPCI'];
                if (in_array($sirenEPCI, array_keys($ecpisImported))) {
                    $epci = $ecpisImported[$sirenEPCI];
                } else {
                    $epci = new Epci();
                }
                $epci->setName($nameEPCI);
                $epci->setSlug($slugger->slug(strtolower((string) $nameEPCI))->toString());
                $epci->setSiren($sirenEPCI);
                foreach ($cityCodes[$sirenEPCI] as $city) {
                    $epci->addCity($city);
                    /** @var City $city */
                    $city->addEpci($epci);
                }
                $manager->persist($epci);
                $ecpisImported[$sirenEPCI] = $epci;

                /** IMPORT DES TERRITOIRES */
                $nameTerritoire = trim($e['Office de tourisme ']);
                $areaTerritoire = TerritoireAreaEnum::OT;
                $customTerritoires = ['CA de Chaumont', 'CC des Savoir-Faire', 'CC Meuse Rognon', 'CC du Bassin de Joinville en Champagne', 'CC des Trois Forêts', 'CC du Grand Langres', 'CC Auberive Vingeanne et Montsaugeonnais'];
                if ($nameTerritoire === '' && in_array(trim($e['Nom_EPCI']), $customTerritoires)) {
                    $nameTerritoire = trim($e['Nom_EPCI']);
                    $areaTerritoire = TerritoireAreaEnum::TOURISME;
                }
                $territoireSlug = $slugger->slug(strtolower($nameTerritoire))->toString();
                if (in_array($territoireSlug, array_keys($territoiresImported))) {
                    $territoire = $territoiresImported[$territoireSlug];
                } else {
                    $territoire = new Territoire();
                }
                $territoire->setUuid(new Ulid());
                $territoire->setName($nameTerritoire);
                $territoire->setSlug($territoireSlug);
                $territoire->setUseSlug(true);
                $territoire->setIsPublic(true);
                // si le slug se finit par un chiffre, on est sur un ot séparé en 2 départements
                if (preg_match('#\d+$#', $territoire->getSlug(), $matches)) {
                    $territoire->addParent($departements[(int) $matches[0]]);
                } else {
                    foreach ($departementsCodes[$sirenEPCI] as $departmentParentCode) {
                        if ((int) $departmentParentCode === 67 || (int) $departmentParentCode === 68) {
                            $territoire->addParent($departements['67|68']);
                        } else {
                            $territoire->addParent($departements[$departmentParentCode]);
                        }
                    }
                }
                if ($nameTerritoire === 'Destination Nancy' || $nameTerritoire === 'Destination Vittel'){
                    foreach ($ot2s as $ot2) {
                        $cityOt2 = $this->cityRepository->findOneBy(['insee' => $ot2]);
                        if ($cityOt2) {
                            $territoire->addCity($cityOt2);
                        }
                    }
                } else {
                    foreach ($cityCodes[$sirenEPCI] as $city) {
                        if (in_array($territoire->getName(), ['Lac du Der en Champagne 51', 'Lac du Der en Champagne 52', 'Mad et Moselle 54', 'Mad et Moselle 57', "Vosges Portes d'Alsace 54", "Vosges Portes d'Alsace 88"])) {
                            $this->handleSplitTerritoiresCities($territoire, $allCities);
                        } else {
                            $territoire->addCity($allCities[$city->getInsee()]);
                        }
                    }
                }
                $territoire->setArea($areaTerritoire);
                $territoire->addEpci($epci);
                $epci->addTerritoire($territoire);
                $manager->persist($territoire);
                $territoiresImported[$territoireSlug] = $territoire;
            }

            $splitTerritoires = [
                "Lac Du Der en Champagne" => [51001, 51008, 51016, 51017, 51022, 51059, 51065, 51066, 51080, 51084, 51125, 51134, 51135, 51141, 51144, 51156, 51167, 51169, 51184, 51195, 51215, 51219, 51220, 51223, 51224, 51246, 51262, 51269, 51270, 51275, 51277, 51284, 51286, 51288, 51295, 51296, 51300, 51315, 51316, 51322, 51328, 51334, 51340, 51349, 51352, 51356, 51358, 51361, 51373, 51406, 51417, 51419, 51446, 51463, 51475, 51478, 51508, 51513, 51520, 51521, 51522, 51528, 51550, 51551, 51552, 51557, 51567, 51583, 51649, 51654,52006,52021,52034,52045,52079,52088,52099,52104,52123,52156,52169,52171,52179,52182,52194,52198,52203,52206,52235,52244,52265,52266,52267,52294,52300,52302,52327,52331,52336,52341,52347,52370,52386,52391,52411,52413,52414,52429,52448,52475,52479,52487,52497,52500,52502,52510,52528,52534,52543,52550],
                "Mad et Mozelle" => [54022,54055,54057,54063,54087,54112,54119,54153,54166,54182,54187,54193,54200,54244,54248,54249,54275,54316,54317,54340,54343,54353,54410,54416,54435,54441,54453,54470,54477,54499,54511,54518,54535,54544,54564,54566,54570,54593,54594,54599, 57021,57030,57153,57254,57350,57416,57515,57578],
                "Vosges Portes d'Alsace" => [54075, 54427, 54443,88005,88009,88014,88032,88033,88035,88053,88054,88057,88059,88064,88068,88082,88089,88093,88106,88111,88113,88115,88120,88128,88159,88165,88181,88182,88193,88198,88213,88215,88244,88245,88268,88275,88276,88277,88284,88300,88306,88315,88317,88319,88320,88326,88328,88341,88345,88346,88349,88356,88361,88362,88372,88373,88375,88386,88398,88413,88419,88423,88424,88428,88435,88436,88438,88444,88445,88451,88463,88501,88503,88505,88506,88519,88526],
            ];
            foreach ($splitTerritoires as $splitTerritoire => $cityInsees) {
                $territoire = new Territoire();
                $areaTerritoire = TerritoireAreaEnum::OT;
                $territoireSlug = $slugger->slug(strtolower($splitTerritoire))->toString();
                $territoire->setUuid(new Ulid());
                $territoire->setName($splitTerritoire);
                $territoire->setSlug($territoireSlug);
                $territoire->setUseSlug(true);
                $territoire->setIsPublic(true);
                if ($splitTerritoire === 'Lac Du Der en Champagne') {
                    $territoire->addParent($departements['51']);
                    $territoire->addParent($departements['52']);
                }
                if ($splitTerritoire === 'Mad et Mozelle') {
                    $territoire->addParent($departements['54']);
                    $territoire->addParent($departements['57']);
                }
                if ($splitTerritoire === 'Vosges Portes d\'Alsace') {
                    $territoire->addParent($departements['67|68']);
                    $territoire->addParent($departements['88']);
                }
                foreach ($cityInsees as $cityInsee) {
                    $territoire->addCity($allCities[(string) $cityInsee]);
                }
                $territoire->setArea($areaTerritoire);
                $manager->persist($territoire);
            }

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            CitiesFixtures::class
        ];
    }

    private function handleSplitTerritoiresCities(mixed $territoire, array $allCities): void
    {
        if ($territoire->getName() === 'Lac du Der en Champagne 51') {
            $insees = [51001, 51008, 51016, 51017, 51022, 51059, 51065, 51066, 51080, 51084, 51125, 51134, 51135, 51141, 51144, 51156, 51167, 51169, 51184, 51195, 51215, 51219, 51220, 51223, 51224, 51246, 51262, 51269, 51270, 51275, 51277, 51284, 51286, 51288, 51295, 51296, 51300, 51315, 51316, 51322, 51328, 51334, 51340, 51349, 51352, 51356, 51358, 51361, 51373, 51406, 51417, 51419, 51446, 51463, 51475, 51478, 51508, 51513, 51520, 51521, 51522, 51528, 51550, 51551, 51552, 51557, 51567, 51583, 51649, 51654];
        }
        if ($territoire->getName() === 'Lac du Der en Champagne 52') {
            $insees = [52006,52021,52034,52045,52079,52088,52099,52104,52123,52156,52169,52171,52179,52182,52194,52198,52203,52206,52235,52244,52265,52266,52267,52294,52300,52302,52327,52331,52336,52341,52347,52370,52386,52391,52411,52413,52414,52429,52448,52475,52479,52487,52497,52500,52502,52510,52528,52534,52543,52550];
        }
        if ($territoire->getName() === 'Mad et Moselle 54') {
            $insees = [54022,54055,54057,54063,54087,54112,54119,54153,54166,54182,54187,54193,54200,54244,54248,54249,54275,54316,54317,54340,54343,54353,54410,54416,54435,54441,54453,54470,54477,54499,54511,54518,54535,54544,54564,54566,54570,54593,54594,54599];
        }
        if ($territoire->getName() === 'Mad et Moselle 57') {
            $insees = [57021,57030,57153,57254,57350,57416,57515,57578];
        }
        if ($territoire->getName() === "Vosges Portes d'Alsace 54") {
            $insees = [54075, 54427, 54443];
        }
        if ($territoire->getName() === "Vosges Portes d'Alsace 88") {
            $insees = [88005,88009,88014,88032,88033,88035,88053,88054,88057,88059,88064,88068,88082,88089,88093,88106,88111,88113,88115,88120,88128,88159,88165,88181,88182,88193,88198,88213,88215,88244,88245,88268,88275,88276,88277,88284,88300,88306,88315,88317,88319,88320,88326,88328,88341,88345,88346,88349,88356,88361,88362,88372,88373,88375,88386,88398,88413,88419,88423,88424,88428,88435,88436,88438,88444,88445,88451,88463,88501,88503,88505,88506,88519,88526];
        }
        foreach ($insees as $insee) {
            $territoire->addCity($allCities[$insee]);
        }
    }
}
