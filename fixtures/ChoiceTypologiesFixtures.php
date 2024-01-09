<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Choice;
use App\Entity\ChoiceTypologie;
use App\Entity\Question;
use App\Entity\Thematique;
use App\Enum\TypologieEnum;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\TypologieRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ChoiceTypologiesFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var string */
    private const COLUMN_REPONSE = 'Réponse';

    public function __construct(
        private readonly ChoiceRepository $choiceRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var string $datasDirectory */
        $datasDirectory = $this->parameterBag->get('datas_directory');
        $ponderationsFile = file_get_contents($datasDirectory . '/ponderations2.csv');
        if ($ponderationsFile) {
            $csvEncoder = new CsvEncoder();
            $ponderationsDatas = $csvEncoder->decode($ponderationsFile, 'csv');
            $datas = array_map(fn(array $row): array => [self::COLUMN_REPONSE => $row[self::COLUMN_REPONSE], ...array_slice($row,  2)], $ponderationsDatas);
        }

        foreach ($datas as $data){
            if (array_filter($data) === []) {
                continue;
            }

            foreach ($data as $columnName => $ponderation) {
                if ($columnName === self::COLUMN_REPONSE || $ponderation === 'N/A') {
                    continue;
                }

                $typologie = match ($columnName) {
                    'hotel', 'hotel avec restaurant' => TypologieEnum::HOTEL,
                    'location', 'location avec restaurant' => TypologieEnum::LOCATION,
                    'chambre', 'chambre avec restaurant' => TypologieEnum::CHAMBRE,
                    'camping', 'camping avec restaurant' => TypologieEnum::CAMPING,
                    'insolite', 'insolite avec restaurant' => TypologieEnum::INSOLITE,
                    'lieu de visite', 'lieu de visite avec restaurant' => TypologieEnum::VISITE,
                    'loisir', 'loisir avec restaurant' => TypologieEnum::ACTIVITE,
                    'restaurant' => TypologieEnum::RESTAURANT,
                    default => null,
                };

                $restauration = str_contains($columnName, 'restaurant');

                if (!$typologie) {
                    throw new \Exception('typologie ne correspond pas : ' . $columnName);
                }

                $reponse = (new AsciiSlugger())->slug(strtolower((string) $data[self::COLUMN_REPONSE]))->toString();

                $choice = $this->choiceRepository->findOneBy(['slug' => $reponse]);
                if (!$choice) {
                    throw new \Exception('slug ne correspond pas : ' . $reponse);
                }

                $typologie = $this->typologieRepository->findOneBy(['slug' => $typologie->value]);
                if (!$typologie) {
                    throw new \Exception('findOneBy typologieSlug ne correspond pas : ' . $typologie);
                }

                if ($this->choiceTypologieRepository->findOneBy(['choice' => $choice, 'typologie' => $typologie, 'restauration' => $restauration])) {
                    continue;
                }

                $crt = new ChoiceTypologie();
                $crt->setChoice($choice);
                $crt->setTypologie($typologie);
                $crt->setRestauration($restauration);
                $crt->setPonderation((int) $ponderation);
                $manager->persist($crt);
                $manager->flush();
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            QuestionsFixtures::class
        ];
    }
}
