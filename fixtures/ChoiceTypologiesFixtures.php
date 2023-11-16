<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Choice;
use App\Entity\ChoiceTypologie;
use App\Entity\Question;
use App\Entity\Thematique;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\TypologieRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $ponderationsFile = file_get_contents('/var/www/html/var/ponderations.csv');
        if ($ponderationsFile) {
            $csvEncoder = new CsvEncoder();
            $ponderationsDatas = $csvEncoder->decode($ponderationsFile, 'csv');
            $datas = array_map(fn(array $row): array => [self::COLUMN_REPONSE => $row[self::COLUMN_REPONSE], ...array_slice($row,  7, 9)], $ponderationsDatas);
        }

        foreach ($datas as $key => $data){
            if ($key == 0) {
                continue;
            }
            if (array_filter($data) === []) {
                continue;
            }
            if ($data[self::COLUMN_REPONSE] === 'TOTAL POINT') {
                break;
            }

            foreach ($data as $typo => $ponderation) {
                if ($typo === self::COLUMN_REPONSE) {
                    continue;
                }

                $typologie = match ($typo) {
                    'A', 'B' => 'hotel',
                    'C', 'D' => 'camping',
                    'E', 'F' => 'visite',
                    'G', 'H' => 'activite',
                    'I' => 'restaurant',
                    default => null,
                };

                $restauration = match ($typo) {
                    'B', 'D', 'F', 'H', 'I' => true,
                    default => false,
                };

                if (!$typologie) {
                    throw new \Exception('typologie ne correspond pas : ' . $typo);
                }

                $reponse = (new AsciiSlugger())->slug(strtolower((string) $data[self::COLUMN_REPONSE]))->toString();

                $choice = $this->choiceRepository->findOneBy(['slug' => $reponse]);
                if (!$choice) {
                    throw new \Exception('slug ne correspond pas : ' . $reponse);
                }

                $typologie = $this->typologieRepository->findOneBy(['slug' => $typologie]);
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
