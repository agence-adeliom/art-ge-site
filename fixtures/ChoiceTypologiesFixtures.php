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
        $choice = $this->choiceRepository->findOneBy(['slug' => 'j-ai-amenage-un-jardin-avec-differentes-herbes-aromatiques-sur-au-moins-1-du-terrain']);
        $typologie = $this->typologieRepository->findOneBy(['slug' => 'hotel']);
        $crt = new ChoiceTypologie();
        $crt->setChoice($choice);
        $crt->setTypologie($typologie);
        $crt->setRestauration(true);
        $crt->setGreenSpace(true);
        $crt->setPonderation(1);
        $manager->persist($crt);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionsFixtures::class
        ];
    }
}
