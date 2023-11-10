<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'ulid')]
    private Ulid $uuid;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private Repondant $repondant;

    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\Column(options: ['comment' => 'Date du commencement du formulaire'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true, options: ['comment' => 'Date de la soumission du formulaire'])]
    private ?\DateTimeImmutable $submittedAt = null;

    #[ORM\Column(nullable: true, options: ['comment' => 'Somme des points obtenus'])]
    private float $points;

    #[ORM\Column(options: ['comment' => 'Somme des points possible d\'obtenir'])]
    private int $total;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Choice::class)]
    private Collection $choices;

    /**
     * @var array<mixed> $rawForm
     */
    #[ORM\Column]
    private array $rawForm = [];

    /**
     * @var array<mixed> $processedForm
     */
    #[ORM\Column]
    private array $processedForm = [];

    #[ORM\OneToMany(mappedBy: 'reponse', targetEntity: Score::class, orphanRemoval: true)]
    #[ORM\OrderBy(["thematique" => "ASC"])]
    private Collection $scores;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Ulid
    {
        return $this->uuid;
    }

    public function setUuid(Ulid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getRepondant(): Repondant
    {
        return $this->repondant;
    }

    public function setRepondant(Repondant $repondant): static
    {
        $this->repondant = $repondant;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?\DateTimeImmutable $submittedAt): static
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points): static
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
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): static
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): static
    {
        $this->choices->removeElement($choice);

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getRawForm(): array
    {
        return $this->rawForm;
    }

    /**
     * @param array<mixed> $rawForm
     */
    public function setRawForm(array $rawForm): static
    {
        $this->rawForm = $rawForm;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getProcessedForm(): array
    {
        return $this->processedForm;
    }

    /**
     * @param array<mixed> $processedForm
     */
    public function setProcessedForm(array $processedForm): static
    {
        $this->processedForm = $processedForm;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setReponse($this);
        }

        return $this;
    }
}
