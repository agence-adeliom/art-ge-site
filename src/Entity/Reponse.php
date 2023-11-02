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
    private ?Ulid $uuid = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Repondant $repondant = null;

    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\Column(options: ['comment' => 'Date du commencement du formulaire'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true, options: ['comment' => 'Date de la soumission du formulaire'])]
    private ?\DateTimeImmutable $submittedAt = null;

    #[ORM\Column(nullable: true, options: ['comment' => 'Somme des points obtenus'])]
    private ?float $points = null;

    #[ORM\Column(options: ['comment' => 'Somme des points possible d\'obtenir'])]
    private ?int $total = null;

    #[ORM\ManyToMany(targetEntity: Choice::class, inversedBy: 'reponses')]
    private Collection $choices;

    #[ORM\Column]
    private array $form = [];

    #[ORM\OneToMany(mappedBy: 'reponse', targetEntity: Score::class, orphanRemoval: true)]
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

    public function getUuid(): ?Ulid
    {
        return $this->uuid;
    }

    public function setUuid(Ulid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getRepondant(): ?Repondant
    {
        return $this->repondant;
    }

    public function setRepondant(?Repondant $repondant): static
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

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getTotal(): ?int
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

    public function getForm(): array
    {
        return $this->form;
    }

    public function setForm(array $form): static
    {
        $this->form = $form;

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

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getReponse() === $this) {
                $score->setReponse(null);
            }
        }

        return $this;
    }
}
