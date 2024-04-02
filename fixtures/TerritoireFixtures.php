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
                foreach ($departementsCodes[$sirenEPCI] as $departmentParentCode) {
                    if ((int) $departmentParentCode === 67 || (int) $departmentParentCode === 68) {
                        $territoire->addParent($departements['67|68']);
                    } else {
                        $territoire->addParent($departements[$departmentParentCode]);
                    }
                }
                foreach ($cityCodes[$sirenEPCI] as $city) {
                    $territoire->addCity($allCities[$city->getInsee()]);
                }
                $territoire->setArea($areaTerritoire);
                $territoire->addEpci($epci);
                $epci->addTerritoire($territoire);
                $manager->persist($territoire);
                $territoiresImported[$territoireSlug] = $territoire;
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
}
