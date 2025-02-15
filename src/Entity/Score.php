<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private Reponse $reponse;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private Thematique $thematique;

    #[ORM\Column(options: ['comment' => 'Somme des points obtenus'])]
    private int $points;

    #[ORM\Column(options: ['comment' => 'Somme des points possible d\'obtenir'])]
    private int $total;

    /** @var array{chosenChoices: array<Choice>, notChosenChoices: array<Choice>} */
    private array $allChoices = ['chosenChoices' => [], 'notChosenChoices' => []];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): Reponse
    {
        return $this->reponse;
    }

    public function setReponse(Reponse $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getThematique(): Thematique
    {
        return $this->thematique;
    }

    public function setThematique(Thematique $thematique): static
    {
        $this->thematique = $thematique;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getPercentage(): string
    {
        if ($this->getTotal() <= 0) {
            return 0 . '%';
        }

        return round(($this->getPoints() / $this->getTotal()) * 100) . '%';
    }

    /**
     * @return array{chosenChoices: array<Choice>, notChosenChoices: array<Choice>}
     */
    public function getAllChoices(): array
    {
        // little memoization
        if (!empty($this->allChoices['chosenChoices'] || !empty($this->allChoices['notChosenChoices']))) {
            return $this->allChoices;
        }

        $rawForm = $this->getReponse()->getRawForm();
        $thematique = $this->getThematique();

        $choices = $thematique->getQuestion()->getChoices()->toArray();
        $this->allChoices['notChosenChoices'] = $choices;

        if (isset($rawForm[$thematique->getId()]['answers'])) {
            $answers = array_keys(array_filter($rawForm[$thematique->getId()]['answers'], fn (string $answer): bool => 'on' === $answer));

            foreach ($choices as $choice) {
                if (in_array($choice->getId(), $answers)) {
                    $this->allChoices['chosenChoices'][] = $choice;
                    foreach ($this->allChoices['notChosenChoices'] as $index => $notChosenChoice) {
                        if ($notChosenChoice->getId() === $choice->getId()) {
                            unset($this->allChoices['notChosenChoices'][$index]);
                        }
                    }
                }
            }
        }

        $this->allChoices['notChosenChoices'] = array_values($this->allChoices['notChosenChoices']);

        return $this->allChoices;
    }

    /**
     * Used in the result page and PDF templates to display the list of chosen
     * choice for the current score displayed (check score.chosenChoices).
     *
     * @return array<Choice>
     */
    public function getChosenChoices(): array
    {
        return $this->getAllChoices()['chosenChoices'];
    }

    /**
     * Used in the result page and PDF templates to display the list of chosen
     * choice for the current score displayed (check score.notChosenChoices).
     *
     * @return array<Choice>
     */
    public function getNotChosenChoices(): array
    {
        return $this->getAllChoices()['notChosenChoices'];
    }
}
