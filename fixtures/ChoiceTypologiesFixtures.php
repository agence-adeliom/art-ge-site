<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Choice;
use App\Entity\ChoiceTypologie;
use App\Entity\Question;
use App\Entity\Thematique;
use App\Repository\ChoiceRepository;
use App\Repository\TypologieRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ChoiceTypologiesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly ChoiceRepository $choiceRepository,
        private readonly TypologieRepository $typologieRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        $test = file_get_contents('/var/www/html/test.csv');
        if ($test) {
            $csvEncoder = new CsvEncoder();
            $decod = $csvEncoder->decode($test, 'csv');
            $datas = array_map(function (array $row) {
                return array_merge(['Réponse' => $row["Réponse"]], array_slice($row,  7, 9));
            }, $decod);

        }

        foreach ($datas as $key => $data){
            if ($key == 0 || array_filter($data) === []) {
                continue;
            }
            if ($data['Réponse'] === 'TOTAL POINT') {
                break;
            }

            foreach ($data as $typo => $ponderation) {
                if ($typo === 'Réponse') {
                    continue;
                }

                $typologie = match ($typo) {
                    'A', 'B' => 'hotel',
                    'C', 'D' => 'camping',
                    'E', 'F', 'G', 'H' => 'loisir',
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

                $reponse = (new AsciiSlugger())->slug(strtolower($data['Réponse']))->toString();

                $choice = $this->choiceRepository->findOneBy(['slug' => $reponse]);
                if (!$choice) {
                    dd($data);
                    throw new \Exception('slug ne correspond pas : ' . $reponse);
                }

                $typologie = $this->typologieRepository->findOneBy(['slug' => $typologie]);
                if (!$typologie) {
                    throw new \Exception('findOneBy typologieSlug ne correspond pas : ' . $typologie);
                }

                $crt = new ChoiceTypologie();
                $crt->setChoice($choice);
                $crt->setTypologie($typologie);
                $crt->setRestauration($restauration);
                $crt->setGreenSpace(true);
                $crt->setPonderation((int) $ponderation);
                $manager->persist($crt);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionsFixtures::class
        ];
    }
}
