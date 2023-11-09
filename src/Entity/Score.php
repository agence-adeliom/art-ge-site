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

    /** @var array<Choice> */
    private array $chosenChoices;

    /** @var array<Choice> */
    private array $notChosenChoices;

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

    /**
     * @return array<Choice>
     */
    public function getChosenChoices(): array
    {
        return $this->chosenChoices;
    }

    /**
     * @param array<Choice> $chosenChoices
     */
    public function setChosenChoices(array $chosenChoices): static
    {
        $this->chosenChoices = $chosenChoices;

        return $this;
    }

    /**
     * @return array<Choice>
     */
    public function getNotChosenChoices(): array
    {
        return $this->notChosenChoices;
    }

    /**
     * @param array<Choice> $notChosenChoices
     */
    public function setNotChosenChoices(array $notChosenChoices): static
    {
        $this->notChosenChoices = $notChosenChoices;

        return $this;
    }
}
