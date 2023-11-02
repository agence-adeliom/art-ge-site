<?php

namespace App\Entity;

use App\Repository\ThematiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThematiqueRepository::class)]
class Thematique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToOne(mappedBy: 'thematique', cascade: ['persist', 'remove'])]
    private ?Question $question = null;

    #[ORM\OneToMany(mappedBy: 'thematique', targetEntity: Score::class, orphanRemoval: true)]
    private Collection $scores;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        // set the owning side of the relation if necessary
        if ($question->getThematique() !== $this) {
            $question->setThematique($this);
        }

        $this->question = $question;

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
            $score->setThematique($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getThematique() === $this) {
                $score->setThematique(null);
            }
        }

        return $this;
    }
}
