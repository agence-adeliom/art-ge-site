<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Department;
use App\Entity\Territoire;
use App\Enum\DepartementEnum;
use App\Enum\TerritoireAreaEnum;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Ulid;

class TerritoireFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();
        $ecpiFile = file_get_contents('/var/www/html/var/datas/ExportCommunes_nov2023.txt');
        if ($ecpiFile) {
            $csvEncoder = new CsvEncoder(['csv_delimiter' => '	']);
            /** @var array{INSEE_commune: string, NOM_COMMUNES: string, Nom_EPCI: string} $ecpiDatas */
            $ecpiDatas = $csvEncoder->decode($ecpiFile, 'csv');


            /** IMPORT DE LA REGION ET DES DEPARTEMENTS */
            $region = new Territoire();
            $region->setUuid(new Ulid());
            $region->setName('Grand-Est');
            $region->setSlug('grand-est');
            $region->setUseSlug(true);
            $region->setIsPublic(true);
            $region->setZips([]);
            $region->setArea(TerritoireAreaEnum::REGION);
            $manager->persist($region);

            $departements = [];
            foreach (DepartementEnum::cases() as $departementEnum) {
                $departement = new Territoire();
                $departement->setUuid(new Ulid());
                $departement->setName(DepartementEnum::getLabel($departementEnum));
                $departement->setSlug($departementEnum->value);
                $departement->setUseSlug(true);
                $departement->setIsPublic(true);
                $departement->setParent($region);
                $departement->setZips([]);
                $departement->setArea(TerritoireAreaEnum::DEPARTEMENT);
                $departements[DepartementEnum::getCode($departementEnum)] = $departement;
                $manager->persist($departement);
            }

            /** IMPORT DES TERRITOIRES */
            $cityCodes = [];
            foreach ($ecpiDatas as $e){
                $inseeCommune = $e['INSEE_commune'];
                $sirenEPCI = $e['sirenEPCI'];
                if (!array_key_exists($sirenEPCI, $cityCodes)) {
                    $cityCodes[$sirenEPCI] = [];
                }
                $city = $this->cityRepository->findOneBy(['insee' => $inseeCommune]);
                if ($city) {
                    $cityCodes[$sirenEPCI][] = $inseeCommune;
                }
            }

            $departementsCodes = [];
            foreach ($cityCodes as $k => $v){
                $departementsCodes[$k] = array_unique(array_map(fn($v): string => substr((string) $v, 0, 2), $v));
            }

            $ecpisImported = [];
            foreach ($ecpiDatas as $e){
                $nameEPCI = $e['Nom_EPCI'];
                $sirenEPCI = $e['sirenEPCI'];
                if (in_array($sirenEPCI, $ecpisImported)) {
                    continue;
                }
                $territoire = new Territoire();
                $territoire->setUuid(new Ulid());
                $territoire->setName($nameEPCI);
                $territoire->setSlug($slugger->slug(strtolower((string) $nameEPCI))->toString());
                $territoire->setUseSlug(true);
                $territoire->setIsPublic(true);
                foreach ($departementsCodes[$sirenEPCI] as $departmentParentCode) {
                    $territoire->setParent($departements[$departmentParentCode]);
                }
                $territoire->setZips($cityCodes[$sirenEPCI]);
                $territoire->setArea(TerritoireAreaEnum::OT);
                $manager->persist($territoire);
                $ecpisImported[] = $sirenEPCI;
                $manager->flush();
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
